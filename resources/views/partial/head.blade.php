<meta charset="utf-8" />
<title>Apex Assign | @yield('title')</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="description" content="@yield('metaDescription')" />
<meta name="author" content="@yield('metaAuthor')" />
<meta name="keywords" content="@yield('metaKeywords')" />

@stack('metaTag')

<!-- ================== BEGIN BASE CSS STYLE ================== -->
<link href="/assets/css/vendor.min.css" rel="stylesheet" />
<link href="/assets/css/app.min.css" rel="stylesheet" />
<link rel="icon" href="/assets/img/favicon.png"/>
<!-- ================== END BASE CSS STYLE ================== -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stack('css')
