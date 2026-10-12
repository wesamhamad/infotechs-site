<?php
/**
 * Tiny two-language layer (en / ar) without a plugin.
 * Language comes from ?lang=xx, then the cookie, then English. __t('key') returns the string.
 */
function infotechs_lang(): string {
    static $lang = null;
    if ($lang !== null) return $lang;
    $req = $_GET['lang'] ?? $_COOKIE['infotechs_lang'] ?? 'en';
    $lang = in_array($req, ['en', 'ar'], true) ? $req : 'en';
    return $lang;
}
add_action('init', function () {
    if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'ar'], true) && !headers_sent()) {
        setcookie('infotechs_lang', $_GET['lang'], time() + YEAR_IN_SECONDS, '/');
        $_COOKIE['infotechs_lang'] = $_GET['lang'];
    }
});
add_filter('language_attributes', fn($a) => infotechs_lang() === 'ar' ? 'lang="ar" dir="rtl"' : 'lang="en-US" dir="ltr"');
add_filter('body_class', function ($c) { $c[] = 'lang-' . infotechs_lang(); return $c; });

function infotechs_strings(): array {
    return [
        // header
        'nav.services' => ['Services', 'خدماتنا'], 'nav.events' => ['Events', 'الفعاليات'], 'nav.approach' => ['Approach', 'منهجيتنا'], 'nav.about' => ['About', 'من نحن'],
        'cta.plan' => ['Plan an event', 'نظّم فعاليتك'], 'cta.events' => ['See our events', 'شاهد فعالياتنا'], 'lang.switch' => ['العربية', 'English'], 'menu' => ['Menu', 'القائمة'],
        // hero
        'hero.eyebrow' => ['Exhibitions · Conferences · Virtual Academy', 'معارض · مؤتمرات · أكاديمية افتراضية'],
        'hero.h1a' => ['Every booth, every seat,', 'كل جناح، كل مقعد،'], 'hero.h1b' => ['planned to the metre.', 'مخطط حتى آخر متر.'],
        'hero.p' => ['Infotechs organises scientific meetings, medical exhibitions and workshops in Riyadh, from the floor plan to the last CME certificate.', 'إنفوتيك تنظّم الاجتماعات العلمية والمعارض الطبية وورش العمل في الرياض، من مخطط القاعة إلى آخر شهادة تعليم طبي مستمر.'],
        'hero.scroll' => ['Scroll', 'انزل'],
        // plan
        'plan.eyebrow' => ['How it starts', 'هكذا تبدأ'], 'plan.h2' => ['A blank hall becomes a plan,<br>a plan becomes Infotechs.', 'من قاعةٍ فارغة يُرسَم المخطط،<br>ومن المخطط تُولد إنفوتيك.'], 'plan.replay' => ['Replay', 'إعادة'],
        'plan.hall' => ['HALL A · RIYADH', 'القاعة أ · الرياض'], 'plan.drawing' => ['DRAWING FLOOR PLAN', 'رسم المخطط'], 'plan.assembling' => ['ASSEMBLING', 'تجميع'], 'plan.ready' => ['READY', 'جاهز'], 'plan.booths' => ['BOOTHS', 'جناح'],
        // stats
        'stat.events' => ['Events delivered', 'فعالية منفّذة'], 'stat.participants' => ['Participants, SAAM 2025', 'مشارك، SAAM 2025'], 'stat.exhibitors' => ['Exhibitors per show', 'عارض في المعرض'], 'stat.cme' => ['CME hours issued, 2025', 'ساعة تعليم طبي، 2025'],
        // services
        'svc.eyebrow' => ['Our expertise', 'خبراتنا'], 'svc.h2' => ['Three things we do, end to end', 'ثلاث خدمات ننفّذها من البداية للنهاية'], 'svc.p' => ['Keep scrolling. The cards stack like booths going up on build day.', 'استمر بالنزول. الكروت تتراكم مثل الأجنحة يوم التجهيز.'],
        'svc1.t' => ['Conferences & scientific meetings', 'المؤتمرات والاجتماعات العلمية'], 'svc1.p' => ['Agenda design, speaker coordination, abstract handling, CME accreditation and on-site registration for 200 to 3,000 delegates.', 'تصميم البرنامج العلمي، تنسيق المتحدثين، إدارة الملخصات، اعتماد ساعات التعليم الطبي المستمر، والتسجيل في الموقع لما بين 200 و3,000 مشارك.'],
        'svc1.tags' => ['Scientific committee|CME accreditation|Registration desk|Abstract review', 'اللجنة العلمية|اعتماد CME|مكتب التسجيل|مراجعة الملخصات'],
        'svc2.t' => ['Exhibition design & management', 'تصميم وإدارة المعارض'], 'svc2.p' => ['Floor plans, booth allocation, sponsor packages and exhibitor logistics for pharma and aesthetic brands.', 'مخططات القاعات، توزيع الأجنحة، باقات الرعاية، ولوجستيات العارضين لشركات الأدوية والتجميل.'],
        'svc2.tags' => ['Floor plan & booths|Sponsor packages|Booth build|Exhibitor logistics', 'المخطط والأجنحة|باقات الرعاية|تجهيز الأجنحة|لوجستيات العارضين'],
        'svc3.t' => ['Virtual academy & webinars', 'الأكاديمية الافتراضية والندوات'], 'svc3.p' => ['Live symposia, recorded courses and certificate issuing for professionals who cannot travel to the hall.', 'ندوات مباشرة، دورات مسجلة، وإصدار شهادات للمختصين الذين لا يستطيعون الحضور.'],
        'svc3.tags' => ['Live streaming|Recorded courses|E-certificates|Attendance reports', 'بث مباشر|دورات مسجلة|شهادات إلكترونية|تقارير الحضور'],
        // partners
        'partners' => ['Partners and sponsors', 'الشركاء والرعاة'], 'partners.hint' => ['scroll speed drives the belt', 'سرعة النزول تحرك الشريط'],
        // events
        'ev.eyebrow' => ['Our events', 'فعالياتنا'], 'ev.h2' => ['Recent shows and courses', 'أحدث المعارض والدورات'], 'ev.all' => ['All %d events →', 'كل الفعاليات (%d) ←'],
        'ev.date' => ['Date', 'التاريخ'], 'ev.venue' => ['Venue', 'المكان'], 'ev.scale' => ['Scale', 'الحجم'], 'ev.page' => ['Event page', 'صفحة الفعالية'], 'ev.back' => ['← All events', '→ كل الفعاليات'],
        // approach
        'ap.eyebrow' => ['Our approach', 'منهجيتنا'], 'ap.h2' => ['From a blank hall to a full one', 'من قاعة فارغة إلى قاعة ممتلئة'], 'phase' => ['PHASE', 'المرحلة'],
        'ap1.t' => ['Brief & floor plan', 'الموجز ومخطط القاعة'], 'ap1.p' => ['We map the venue, size the booths and set the delegate flow before any sponsor is called. The plan is the contract everyone works from.', 'نرسم القاعة، نحدد مقاسات الأجنحة ومسار الحضور قبل التواصل مع أي راعٍ. المخطط هو المرجع الذي يعمل عليه الجميع.'],
        'ap2.t' => ['Programme & speakers', 'البرنامج والمتحدثون'], 'ap2.p' => ['Scientific committee, invitations, CME submission and abstract review. Every session gets a room, a chair and a timing.', 'اللجنة العلمية، الدعوات، تقديم ملف CME ومراجعة الملخصات. كل جلسة لها قاعة ورئيس وتوقيت.'],
        'ap3.t' => ['Sponsors & exhibitors', 'الرعاة والعارضون'], 'ap3.p' => ['Packages, contracts, booth build and branding, tracked in one sheet. Sponsor booths light up on the plan as they sign.', 'الباقات، العقود، تجهيز الأجنحة والهوية، كلها في جدول واحد. أجنحة الرعاة تضيء على المخطط مع كل توقيع.'],
        'ap4.t' => ['Show day & evaluation', 'يوم الفعالية والتقييم'], 'ap4.p' => ['Registration desk, AV, catering, then a post-event report with attendance and feedback.', 'مكتب التسجيل، الصوتيات والمرئيات، الضيافة، ثم تقرير ما بعد الفعالية بالحضور والتقييم.'],
        // gallery
        'g.eyebrow' => ['On the floor', 'من أرض الحدث'], 'g.h2' => ['SAAM, three editions in pictures', 'مؤتمر SAAM، ثلاث نسخ في صور'], 'g.p' => ['Columns move at their own pace as you scroll.', 'كل عمود يتحرك بسرعته مع النزول.'],
        // about
        'ab.eyebrow' => ['About Infotechs', 'عن إنفوتيك'], 'ab.h2' => ['For organizing exhibitions and conferences', 'لتنظيم المعارض والمؤتمرات'],
        'ab.intro' => ['Infotechs is a premier event and exhibition management company based in Riyadh, Saudi Arabia. We specialize in organizing scientific meetings and workshops, as well as providing a virtual academy for professional development.', 'إنفوتيك شركة رائدة في إدارة الفعاليات والمعارض، مقرها الرياض، المملكة العربية السعودية. نتخصص في تنظيم الاجتماعات العلمية وورش العمل، ونقدّم أكاديمية افتراضية للتطوير المهني.'],
        'ab.mission.k' => ['Our mission', 'رسالتنا'], 'ab.mission' => ['To organize scientific meetings, exhibitions and workshops that bring the medical community together, and to extend that learning online through our virtual academy.', 'تنظيم اجتماعات علمية ومعارض وورش عمل تجمع المجتمع الطبي، وامتداد هذا التعلّم عبر أكاديميتنا الافتراضية.'],
        'ab.vision.k' => ['Our vision', 'رؤيتنا'], 'ab.vision' => ['To be the first choice for scientific and medical events in Saudi Arabia, where every conference is planned to the metre and every participant leaves with knowledge they can use.', 'أن نكون الخيار الأول للفعاليات العلمية والطبية في المملكة، حيث يُخطط كل مؤتمر حتى آخر متر، ويغادر كل مشارك بمعرفة يستطيع تطبيقها.'],
        'ab.team.k' => ['Our team', 'فريقنا'], 'ab.team' => ['Our team, equipped with extensive medical and technical expertise, is dedicated to fostering knowledge sharing and collaboration in a professional environment.', 'فريقنا، بخبراته الطبية والتقنية الواسعة، ملتزم بتعزيز تبادل المعرفة والتعاون في بيئة مهنية.'],
        'ab.values.k' => ['Why Infotechs', 'لماذا إنفوتيك'],
        'v1.t' => ['Proven track record', 'سجل مثبت'], 'v1.p' => ['Four editions of SAAM, trichology and hepatology courses, osteoporosis days: delivered.', 'أربع نسخ من SAAM، دورات الشعر والكبد، وأيام هشاشة العظام: منفّذة.'],
        'v2.t' => ['Strong partnerships', 'شراكات قوية'], 'v2.p' => ['Scientific societies, pharma and aesthetic brands that return edition after edition.', 'جمعيات علمية وشركات أدوية وتجميل تعود معنا نسخة بعد نسخة.'],
        'v3.t' => ['Comprehensive support', 'دعم شامل'], 'v3.p' => ['From the first brief to the post-event report, one team handles it all.', 'من الموجز الأول إلى تقرير ما بعد الفعالية، فريق واحد يتولى كل شيء.'],
        'v4.t' => ['Tailored solutions', 'حلول مخصصة'], 'v4.p' => ['Every programme, floor plan and sponsor package is built for that event.', 'كل برنامج ومخطط وباقة رعاية تُبنى لتلك الفعالية تحديداً.'],
        'v5.t' => ['Sustainability commitment', 'التزام بالاستدامة'], 'v5.p' => ['Digital registration, e-certificates and less printed waste on the floor.', 'تسجيل رقمي، شهادات إلكترونية، وهدر مطبوعات أقل في القاعة.'],
        'v6.t' => ['Post-event evaluation', 'تقييم ما بعد الفعالية'], 'v6.p' => ['Attendance, feedback and CME data in a report you can act on.', 'الحضور والتقييم وبيانات CME في تقرير يمكنك البناء عليه.'],
        'ab.based' => ['Based in Riyadh, Saudi Arabia', 'مقرنا الرياض، المملكة العربية السعودية'],
        // cta / footer
        'cta.eyebrow' => ['Next event', 'فعاليتك القادمة'], 'cta.h2' => ["Let's draw your floor plan.", 'لنرسم مخطط قاعتك.'],
        'cta.p' => ['Tell us the venue, the dates and the audience. We come back with a plan, a budget and a sponsor list within a week.', 'أخبرنا بالمكان والتواريخ والجمهور، ونعود إليك بخطة وميزانية وقائمة رعاة خلال أسبوع.'],
        'footer.tag' => ['For Organizing Exhibitions and Conferences', 'لتنظيم المعارض والمؤتمرات'], 'footer.city' => ['Riyadh, Saudi Arabia', 'الرياض، المملكة العربية السعودية'],

        // nav (pages)
        'nav.home' => ['Home', 'الرئيسية'], 'nav.gallery' => ['Gallery', 'المعرض'], 'nav.careers' => ['Join our team', 'انضم إلينا'], 'nav.contact' => ['Contacts', 'تواصل معنا'],
        // plan (scroll-driven)
        'plan.hint' => ['Scroll to assemble', 'انزل ليتجمع الشعار'],
        // services index
        'sx.eyebrow' => ['Everything we cover', 'كل ما نغطيه'], 'sx.h2' => ['Expertise and services, A to Z', 'الخبرات والخدمات من الألف إلى الياء'], 'sx.p' => ['Hover a line to see it on the floor.', 'مرّر على أي سطر لتراه على أرض الحدث.'],
        'sx.p2' => ['Hover a tile to see it on the floor.', 'مرّر على أي بطاقة لتراها على أرض الحدث.'], 'sx.g1' => ['Our expertise', 'خبراتنا'], 'sx.g2' => ['Our services', 'خدماتنا'],
        'x1.t' => ['Conference Planning', 'تخطيط المؤتمرات'], 'x1.p' => ['Detailed planning services from concept to execution, ensuring every aspect is covered.', 'تخطيط تفصيلي من الفكرة إلى التنفيذ، يغطي كل جانب من جوانب المؤتمر.'],
        'x2.t' => ['Exhibition Design', 'تصميم المعارض'], 'x2.p' => ['Innovative booth designs and layouts that attract attention and engage attendees.', 'تصاميم أجنحة ومخططات مبتكرة تجذب الانتباه وتشرك الحضور.'],
        'x3.t' => ['Speaker Coordination', 'تنسيق المتحدثين'], 'x3.p' => ['Assistance with inviting and coordinating expert speakers and presenters.', 'المساعدة في دعوة وتنسيق المتحدثين والخبراء.'],
        'x4.t' => ['Networking Opportunities', 'فرص التواصل'], 'x4.p' => ['Facilitating networking sessions to connect professionals and foster collaboration.', 'تنظيم جلسات تواصل تربط المهنيين وتعزز التعاون.'],
        's1.t' => ['Event Management', 'إدارة الفعاليات'], 's1.p' => ['Comprehensive planning and execution of conferences, workshops, and exhibitions.', 'تخطيط وتنفيذ شامل للمؤتمرات وورش العمل والمعارض.'],
        's2.t' => ['Virtual Academy', 'الأكاديمية الافتراضية'], 's2.p' => ['Online courses, live symposia and e-certificates for professional development.', 'دورات عبر الإنترنت، ندوات مباشرة وشهادات إلكترونية للتطوير المهني.'],
        's3.t' => ['Venue Planning', 'تخطيط القاعات'], 's3.p' => ['Venue selection, floor plans and delegate flow, sized to the audience.', 'اختيار المكان، مخططات القاعة ومسار الحضور بما يناسب حجم الجمهور.'],
        's4.t' => ['Scientific Support', 'الدعم العلمي'], 's4.p' => ['Assistance with scientific content, speaker coordination, and program development.', 'المساعدة في المحتوى العلمي وتنسيق المتحدثين وتطوير البرنامج.'],
        's5.t' => ['Marketing', 'التسويق'], 's5.p' => ['Strategic marketing campaigns to promote your event effectively and attract attendees.', 'حملات تسويقية استراتيجية للترويج لفعاليتك وجذب الحضور.'],
        's6.t' => ['Broadcasting', 'البث'], 's6.p' => ['Live streaming and broadcasting services for virtual attendance and wider reach.', 'خدمات البث المباشر للحضور الافتراضي ووصول أوسع.'],
        's7.t' => ['Unique IT Services', 'خدمات تقنية متخصصة'], 's7.p' => ['Customized IT solutions to enhance the overall event experience.', 'حلول تقنية مخصصة ترفع تجربة الفعالية.'],
        's8.t' => ['Audio-Visual Solutions', 'الحلول السمعية والبصرية'], 's8.p' => ['High-quality AV setups for seamless presentations and engagement.', 'تجهيزات صوت وصورة عالية الجودة لعروض سلسة وتفاعل أفضل.'],
        's9.t' => ['Registration & Payment', 'التسجيل والدفع'], 's9.p' => ['Streamlined registration processes and secure payment options for attendees.', 'تسجيل سلس وخيارات دفع آمنة للحضور.'],
        's10.t' => ['Travel Logistics', 'لوجستيات السفر'], 's10.p' => ['Flight arrangements, accommodation and transfers for speakers and delegates.', 'ترتيبات الطيران والإقامة والتنقل للمتحدثين والمشاركين.'],
        's11.t' => ['Multimedia & Digital Services', 'الوسائط والخدمات الرقمية'], 's11.p' => ['Website development, event apps, motion graphics and digital signage.', 'تطوير المواقع، تطبيقات الفعاليات، الموشن جرافيك واللوحات الرقمية.'],
        // team
        'tm.eyebrow' => ['Our team', 'فريقنا'], 'tm.h2' => ['The people behind the floor plan', 'الأشخاص خلف المخطط'],
        'tm1.n' => ['Mohammed Alkhowailed, MD', 'د. محمد الخويلد'], 'tm1.r' => ['Managing Director', 'المدير العام'],
        'tm2.n' => ['Saad Altalhab, MD', 'د. سعد الطلحب'], 'tm2.r' => ['CEO', 'الرئيس التنفيذي'],
        'tm3.n' => ['Emadeldeen Abass', 'عماد الدين عباس'], 'tm3.r' => ['Finance', 'المالية'],
        'tm4.n' => ['Mahmoud Zainab', 'محمود زينب'], 'tm4.r' => ['Operation Director', 'مدير العمليات'],
        'tm5.n' => ['Diyana Ghebrekidan', 'ديانا غبريكيدان'], 'tm5.r' => ['Project Specialist', 'أخصائية مشاريع'],
        'tm6.n' => ['Marvin', 'مارفن'], 'tm6.r' => ['Secretary', 'السكرتارية'],
        'tm7.n' => ['Mohammed Bazil', 'محمد بازل'], 'tm7.r' => ['IT Specialist', 'أخصائي تقنية معلومات'],
        'tm8.n' => ['Yousef Al-Atwi', 'يوسف العطوي'], 'tm8.r' => ['Sales & Government Relations', 'المبيعات والعلاقات الحكومية'],
        // events archive
        'eva.eyebrow' => ['Our events', 'فعالياتنا'], 'eva.h1' => ['Explore our latest projects and achievements', 'استكشف أحدث مشاريعنا وإنجازاتنا'], 'eva.all' => ['All', 'الكل'], 'eva.count' => ['%d events', '%d فعالية'],
        'g.more' => ['Open the full gallery →', 'افتح المعرض كاملاً ←'],
        // gallery page
        'gp.eyebrow' => ['Gallery', 'المعرض'], 'gp.h1' => ['Moments from the floor', 'لحظات من أرض الحدث'], 'gp.p' => ['%d photographs from conferences, workshops and exhibitions organised by Infotechs.', '%d صورة من مؤتمرات وورش ومعارض نظمتها إنفوتيك.'],
        // contacts page
        'ct.eyebrow' => ['Contact Infotechs', 'تواصل مع إنفوتيك'], 'ct.h1' => ["We'd love to hear from you", 'يسعدنا سماعك'], 'ct.get' => ['Get in touch', 'تواصل معنا'],
        'ct.address.k' => ['Address', 'العنوان'], 'ct.address' => ['Nawat Investment building, Imam Saud Bin Abdul Aziz Road, Office 70, 2nd floor, Riyadh 12211', 'مبنى نواة للاستثمار، طريق الإمام سعود بن عبدالعزيز، مكتب 70، الدور الثاني، الرياض 12211'],
        'ct.phone.k' => ['Phone', 'الهاتف'], 'ct.email.k' => ['Email', 'البريد'], 'ct.hours.k' => ['Working hours', 'ساعات العمل'],
        'ct.hours1' => ['Sunday – Thursday: 9:00 AM – 6:00 PM', 'الأحد – الخميس: 9:00 ص – 6:00 م'], 'ct.hours2' => ['Friday – Saturday: Closed', 'الجمعة – السبت: مغلق'],
        'ct.map' => ['Open in Google Maps', 'افتح في خرائط قوقل'], 'gp.seen' => ['seen', 'شوهدت'], 'gp.view' => ['View', 'عرض'],
        'ct.copy' => ['Copy', 'نسخ'], 'ct.copied' => ['Copied', 'تم النسخ'], 'ct.open' => ['Open now · Riyadh', 'مفتوح الآن · الرياض'], 'ct.closed' => ['Closed now · Riyadh', 'مغلق الآن · الرياض'],
        'bf.s1' => ['What are you planning?', 'ماذا تخطط؟'], 'bf.t1' => ['Conference', 'مؤتمر'], 'bf.t2' => ['Exhibition', 'معرض'], 'bf.t3' => ['Workshop', 'ورشة عمل'], 'bf.t4' => ['Virtual event', 'فعالية افتراضية'],
        'bf.size' => ['Expected attendees:', 'الحضور المتوقع:'], 'bf.booths' => ['≈ %d booths · %s', '≈ %d جناح · %s'], 'bf.hallS' => ['one hall', 'قاعة واحدة'], 'bf.hallM' => ['main hall + exhibition', 'قاعة رئيسية + معرض'], 'bf.hallL' => ['convention centre', 'مركز مؤتمرات'],
        'bf.s2' => ['When and where?', 'متى وأين؟'], 'bf.when' => ['Target month', 'الشهر المستهدف'], 'bf.city' => ['City', 'المدينة'], 'bf.needs' => ['What do you need from us?', 'ماذا تحتاج منا؟'], 'bf.needs.ph' => ['Scientific programme, sponsors, booths, CME, streaming...', 'برنامج علمي، رعاة، أجنحة، CME، بث...'],
        'bf.s3' => ['How do we reach you?', 'كيف نتواصل معك؟'], 'bf.next' => ['Next →', 'التالي ←'], 'bf.back' => ['← Back', '→ رجوع'], 'bf.send' => ['Send the brief', 'أرسل الموجز'], 'bf.sum' => ['%s · %s attendees', '%s · %s مشارك'],
        'jn.open' => ['Open positions', 'الوظائف المتاحة'], 'jn.open.h2' => ['Pick a role, the form fills itself', 'اختر الوظيفة ويتعبّى النموذج تلقائياً'], 'jn.open.p' => ['Roles listed on infotechs.co. Click one to start your application.', 'الوظائف كما في موقع إنفوتيك. اضغط واحدة لتبدأ طلبك.'],
        'jn.d1' => ['WordPress, event registration portals and the Virtual Academy platform.', 'ووردبريس، بوابات تسجيل الفعاليات ومنصة الأكاديمية الافتراضية.'], 'jn.d2' => ['Posters, booth branding, social media and on-screen graphics for every event.', 'بوسترات، هوية الأجنحة، سوشال ميديا ورسوميات الشاشات لكل فعالية.'],
        'jn.d3' => ['Campaigns that fill the hall: email, social, sponsor decks.', 'حملات تملأ القاعة: بريد، سوشال، عروض الرعاة.'], 'jn.d4' => ['Own an event from brief to post-event report.', 'تولّ فعالية من الموجز إلى تقرير ما بعد الفعالية.'], 'jn.d5' => ["Don't see your role? Tell us what you do best.", 'ما لقيت وظيفتك؟ أخبرنا بما تجيده.'],
        'jn.fulltime' => ['Full time', 'دوام كامل'], 'jn.apply' => ['Apply', 'قدّم'], 'jn.apply.k' => ['Application', 'طلب التوظيف'], 'jn.apply.h2' => ['Tell us about you', 'عرّفنا بنفسك'],
        'jn.progress' => ['Application complete', 'اكتمال الطلب'], 'jn.st1' => ['We read every CV within a week', 'نقرأ كل سيرة ذاتية خلال أسبوع'], 'jn.st2' => ['Short call with the operations director', 'مكالمة قصيرة مع مدير العمليات'], 'jn.st3' => ['Join the crew at the next event', 'انضم للفريق في الفعالية القادمة'],
        'jn.drop' => ['Drop your CV here or click to choose', 'اسحب سيرتك الذاتية هنا أو اضغط للاختيار'], 'jn.f.team' => ['Team members', 'عضو فريق'], 'jn.f.roles' => ['Open roles', 'وظائف متاحة'],
        'ct.send' => ['Send us a message', 'أرسل لنا رسالة'], 'ct.follow' => ['Follow us', 'تابعنا'],
        'f.name' => ['Full name', 'الاسم الكامل'], 'f.email' => ['Email address', 'البريد الإلكتروني'], 'f.phone' => ['Phone number', 'رقم الجوال'], 'f.subject' => ['Subject', 'الموضوع'], 'f.message' => ['Message', 'الرسالة'], 'f.submit' => ['Send message', 'إرسال'],
        'f.sent' => ['Thank you. Your message has been sent.', 'شكراً لك، تم إرسال رسالتك.'], 'f.error' => ['Something went wrong. Please try again or email us directly.', 'حدث خطأ، حاول مرة أخرى أو راسلنا مباشرة.'],
        // join our team page
        'jn.eyebrow' => ['Careers', 'الوظائف'], 'jn.h1' => ['Join our team', 'انضم إلى فريقنا'], 'jn.p' => ["We're always looking for talented individuals to join our growing team.", 'نبحث دائماً عن مواهب تنضم إلى فريقنا المتنامي.'],
        'jn.first' => ['First name', 'الاسم الأول'], 'jn.last' => ['Last name', 'اسم العائلة'], 'jn.position' => ['Position applying for', 'الوظيفة المتقدم لها'], 'jn.choose' => ['Please choose an option', 'اختر وظيفة'],
        'jn.p1' => ['Web Developer', 'مطور ويب'], 'jn.p2' => ['Graphic Designer', 'مصمم جرافيك'], 'jn.p3' => ['Marketing Specialist', 'أخصائي تسويق'], 'jn.p4' => ['Project Manager', 'مدير مشاريع'], 'jn.p5' => ['Other', 'أخرى'],
        'jn.cover' => ['Cover letter', 'خطاب التقديم'], 'jn.cv' => ['Upload your CV (PDF, max 10MB)', 'ارفع سيرتك الذاتية (PDF، حتى 10MB)'], 'jn.submit' => ['Submit application', 'إرسال الطلب'],
        'jn.sent' => ['Thank you. Your application has been received.', 'شكراً لك، تم استلام طلبك.'],
        // footer newsletter
        'nl.k' => ['Newsletter', 'النشرة البريدية'], 'nl.p' => ['Dates and calls for abstracts, once a month.', 'المواعيد ودعوات الملخصات، مرة في الشهر.'], 'nl.btn' => ['Subscribe', 'اشترك'],
        'footer.pages' => ['Pages', 'الصفحات'], 'footer.contact' => ['Contact', 'التواصل'],
        'page.home' => ['← Home', '→ الرئيسية'],
        'modal.close' => ['Close', 'إغلاق'],
    ];
}
function __t(string $key, ...$args): string {
    $s = infotechs_strings()[$key] ?? [$key, $key];
    $v = $s[infotechs_lang() === 'ar' ? 1 : 0];
    return $args ? sprintf($v, ...$args) : $v;
}
/** Event field with Arabic fallback: meta "<field>_ar" when the site is in Arabic. */
function infotechs_ev(int $id, string $field): string {
    if (infotechs_lang() === 'ar') { $ar = get_post_meta($id, $field . '_ar', true); if ($ar !== '') return $ar; }
    if ($field === 'title') return get_the_title($id);
    if ($field === 'excerpt') return get_the_excerpt($id);
    return (string) get_post_meta($id, $field, true);
}
/** URL for switching language, keeps the current page. */
function infotechs_lang_url(string $lang): string { return add_query_arg('lang', $lang, remove_query_arg('lang')); }
