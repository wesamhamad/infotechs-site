#!/usr/bin/env python3
"""Export the local WordPress site to a static folder for GitHub Pages.

Usage: python3 tools/export-static.py [--base /repo-name] [--out docs]
English pages land at <base>/..., Arabic pages at <base>/ar/...
"""
import argparse, os, re, shutil, sys, urllib.request, urllib.parse, http.cookiejar

SRC = 'http://localhost:8088'
ap = argparse.ArgumentParser(); ap.add_argument('--base', default='/infotechs-site'); ap.add_argument('--out', default='docs')
a = ap.parse_args(); BASE = a.base.rstrip('/'); OUT = a.out
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

def fetch(path, lang):
    url = f'{SRC}{path}'; url += ('&' if '?' in url else '?') + f'lang={lang}'
    req = urllib.request.Request(url, headers={'Cookie': f'infotechs_lang={lang}', 'User-Agent': 'export'})
    return urllib.request.urlopen(req).read().decode('utf-8')

def discover():
    pages = {'/', '/events/', '/gallery/', '/contacts/', '/join-our-team/', '/about-us/'}
    html = fetch('/events/', 'en')
    pages |= set(re.findall(r'(?:href|data-link)="http://localhost:8088(/events/[^"/]+/)"', html))
    return sorted(pages)

def rewrite(html, lang):
    prefix = BASE + ('/ar' if lang == 'ar' else '')
    other = BASE + ('' if lang == 'ar' else '/ar')
    # language switch links -> the other static tree
    html = re.sub(r'href="http://localhost:8088(/[^"]*?)\?lang=(ar|en)[^"]*"', lambda m: f'href="{other}{m.group(1)}"', html)
    html = re.sub(r'href="http://localhost:8088/\?lang=(ar|en)[^"]*"', lambda m: f'href="{other}/"', html)
    html = re.sub(r'href="(/[^"?]*)\?lang=(ar|en)[^"]*"', lambda m: f'href="{other}{m.group(1)}"', html)
    # theme assets stay shared
    html = html.replace('http://localhost:8088/wp-content/themes/infotechs/', BASE + '/theme/')
    # uploaded media (event posters)
    html = html.replace('http://localhost:8088/wp-content/uploads/', BASE + '/uploads/')
    # internal links -> language tree
    html = re.sub(r'(href|src|action)="http://localhost:8088(/[^"]*)"', lambda m: f'{m.group(1)}="{prefix}{m.group(2)}"', html)
    html = html.replace('http://localhost:8088', prefix)
    return html

def write(path, lang, html):
    d = os.path.join(ROOT, OUT, 'ar' if lang == 'ar' else '', urllib.parse.unquote(path.strip('/')))
    os.makedirs(d, exist_ok=True)
    with open(os.path.join(d, 'index.html'), 'w', encoding='utf-8') as f: f.write(html)

if os.path.isdir(os.path.join(ROOT, OUT)): shutil.rmtree(os.path.join(ROOT, OUT))
os.makedirs(os.path.join(ROOT, OUT))
open(os.path.join(ROOT, OUT, '.nojekyll'), 'w').close()
shutil.copytree(os.path.join(ROOT, 'theme'), os.path.join(ROOT, OUT, 'theme'), ignore=shutil.ignore_patterns('*.php'))
pages = discover(); uploads = set()
for p in pages:
    for lang in ('en', 'ar'):
        html = fetch(p, lang)
        uploads |= set(re.findall(r'http://localhost:8088/wp-content/uploads/([^"\s]+)', html))
        write(p, lang, rewrite(html, lang))
for u in sorted(uploads):
    dst = os.path.join(ROOT, OUT, 'uploads', u); os.makedirs(os.path.dirname(dst), exist_ok=True)
    if not os.path.exists(dst):
        with urllib.request.urlopen(f'{SRC}/wp-content/uploads/{u}') as r, open(dst, 'wb') as f: shutil.copyfileobj(r, f)
print(f'exported {len(pages)} pages x 2 languages, {len(uploads)} uploads -> {OUT}/')
