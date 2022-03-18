{{-- <!DOCTYPE html> --}}
<html lang="en">
<!--begin::Head-->

<head>
    <meta charset="utf-8" />
    <title>{{ env('APP_NAME') }}</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{ asset('metronic/demo6/dist/assets/plugins/global/plugins.bundle.css?v=7.0.6') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('metronic/demo6/dist/assets/plugins/custom/prismjs/prismjs.bundle.css?v=7.0.6') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic/demo6/dist/assets/css/style.bundle.css?v=7.0.6') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('css/custom.admin.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/custom.admin-responsive.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="{{ asset('metronic/demo6/dist/assets/media/logos/favicon.ico') }}" />

</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled page-loading">
    <!--begin::Main-->
    <!--begin::Header Mobile-->
    <div id="kt_header_mobile" class="header-mobile header-mobile-fixed">
        <!--begin::Logo-->
        <a href="metronic/demo6/index.html">
            {{-- <img alt="Logo" src="{{asset('metronic/demo6/dist/assets/media/logos/logo-letter-1.png')}}" class="logo-default max-h-30px" /> --}}
        </a>
        <!--end::Logo-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center text-white">
            <button class="btn p-0 mobile-toggle-active text-white" id="kt_aside_mobile_toggle">
                เมนู
            </button>
            <button class="btn btn-hover-icon-primary p-0 ml-2" id="kt_aside_mobile_toggle">
                <span class="svg-icon">
                    <!--end::Svg Icon-->
                </span>
            </button>
        </div>
        <!--end::Toolbar-->
    </div>
    <!--end::Header Mobile-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="d-flex flex-row flex-column-fluid page">
            <!--begin::Aside-->
            <div class="aside aside-left d-flex flex-column" id="kt_aside">
                <!--begin::Brand-->
                <div class="aside-brand d-flex flex-column align-items-center flex-column-auto pt-5 pt-lg-18 pb-10">
                    <!--begin::Logo-->
                    <div class="btn p-0 symbol symbol-60 symbol-light-primary" href="metronic/demo6/index.html"
                        id="kt_quick_user_toggle">
                        <div class="symbol-label">
                            <img alt="Logo"
                                src="{{ asset('metronic/demo6/dist/assets/media/svg/avatars/001-boy.svg') }}"
                                class="h-75 align-self-end" />
                        </div>
                    </div>
                    <!--end::Logo-->
                </div>
                <!--end::Brand-->
                <!--begin::Nav Wrapper-->
                <div class="aside-nav d-flex flex-column align-items-center flex-column-fluid pb-10">
                    <!--begin::Nav-->
                    <ul class="nav flex-column">
                        <!--begin::Item-->
                        <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                            data-boundary="window" title="ภาพรวม">
                            <a href="#" class="nav-link btn btn-icon btn-hover-text-primary btn-lg active">
                                <i class="fas fa-layer-group d-block"></i>
                            </a>
                            <span class="d-block text-white">ภาพรวม</span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                            data-boundary="window" title="จัดการแบรนด์">
                            <a href="#" class="nav-link btn btn-icon btn-hover-text-primary btn-lg" data-toggle="tab"
                                data-target="#kt_aside_tab_2" role="tab">
                                <i class="fa la-flag"></i>
                            </a>
                            <span class="d-block text-white">จัดการแบรนด์</span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                            data-boundary="window" title="จัดการผู้ใช้งาน">
                            <a href="#" class="nav-link btn btn-icon btn-hover-text-primary btn-lg" data-toggle="tab"
                                data-target="#kt_aside_tab_3" role="tab">
                                <i class="fa fa-users"></i>
                            </a>
                            <span class="d-block text-white">จัดการผู้ใช้งาน</span>
                        </li>
                        <!--begin::Item-->
                        <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                            data-boundary="window" title="จัดการเกมส์">
                            <a href="#" class="nav-link btn btn-icon btn-hover-text-primary btn-lg" data-toggle="tab"
                                data-target="#kt_aside_tab_3" role="tab">
                                <i class="fa fa-gamepad"></i>
                            </a>
                            <span class="d-block text-white">จัดการเกมส์</span>
                        </li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Nav-->
                </div>
                <!--end::Nav Wrapper-->
                <!--begin::Footer-->
                <div class="aside-footer d-flex flex-column align-items-center flex-column-auto py-8">
                    <!--begin::Notifications-->
                    <a href="#" class="btn btn-icon btn-hover-text-primary btn-lg mb-1 position-relative"
                        id="kt_quick_notifications_toggle" data-toggle="tooltip" data-placement="right"
                        data-container="body" data-boundary="window" title="Notifications">
                        <i class="fas fa-bell"></i>
                        <span
                            class="label label-sm label-light-danger label-rounded font-weight-bolder position-absolute top-0 right-0 mt-1 mr-1">3</span>
                    </a>
                    <!--end::Quick Panel-->
                    <!--begin::Languages-->
                    <div class="dropdown" data-toggle="tooltip" data-placement="right" data-container="body"
                        data-boundary="window" title="Languages">
                        <a href="#" class="btn btn-icon btn-hover-text-primary btn-lg" data-toggle="dropdown"
                            data-offset="0px,0px">
                            <img class="w-20px h-20px rounded"
                                src="{{ asset('metronic/demo6/dist/assets/media/svg/flags/226-united-states.svg') }}"
                                alt="image" />
                        </a>
                        <!--begin::Dropdown-->
                        <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-left">
                            <!--begin::Nav-->
                            <ul class="navi navi-hover py-4">
                                <!--begin::Item-->
                                <li class="navi-item">
                                    <a href="#" class="navi-link">
                                        <span class="symbol symbol-20 mr-3">
                                            <img src="{{ asset('metronic/demo6/dist/assets/media/svg/flags/226-united-states.svg') }}"
                                                alt="" />
                                        </span>
                                        <span class="navi-text">English</span>
                                    </a>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="navi-item active">
                                    <a href="#" class="navi-link">
                                        <span class="symbol symbol-20 mr-3">
                                            <img src="{{ asset('metronic/demo6/dist/assets/media/svg/flags/128-spain.svg') }}"
                                                alt="" />
                                        </span>
                                        <span class="navi-text">Spanish</span>
                                    </a>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="navi-item">
                                    <a href="#" class="navi-link">
                                        <span class="symbol symbol-20 mr-3">
                                            <img src="{{ asset('metronic/demo6/dist/assets/media/svg/flags/162-germany.svg') }}"
                                                alt="" />
                                        </span>
                                        <span class="navi-text">German</span>
                                    </a>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="navi-item">
                                    <a href="#" class="navi-link">
                                        <span class="symbol symbol-20 mr-3">
                                            <img src="{{ asset('metronic/demo6/dist/assets/media/svg/flags/063-japan.svg') }}"
                                                alt="" />
                                        </span>
                                        <span class="navi-text">Japanese</span>
                                    </a>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="navi-item">
                                    <a href="#" class="navi-link">
                                        <span class="symbol symbol-20 mr-3">
                                            <img src="{{ asset('metronic/demo6/dist/assets/media/svg/flags/195-france.svg') }}"
                                                alt="" />
                                        </span>
                                        <span class="navi-text">French</span>
                                    </a>
                                </li>
                                <!--end::Item-->
                            </ul>
                            <!--end::Nav-->
                        </div>
                        <!--end::Dropdown-->
                    </div>
                    <!--end::Languages-->
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Aside-->
            <!--begin::Wrapper-->
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                <!--begin::Header-->
                {{-- <div id="kt_header" class="header header-fixed">
						<!--begin::Header Wrapper-->
						<div class="header-wrapper rounded-top-xl d-flex flex-grow-1 align-items-center">
							<!--begin::Container-->
							<div class="container-fluid d-flex align-items-center justify-content-end justify-content-lg-between flex-wrap">
								<!--begin::Menu Wrapper-->
								<div class="header-menu-wrapper header-menu-wrapper-left py-lg-2" id="kt_header_menu_wrapper">
									<!--begin::Menu-->
									<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
										<!--begin::Nav-->
										<ul class="menu-nav">
											
										</ul>
										<!--end::Nav-->
									</div>
									<!--end::Menu-->
								</div>
								<!--end::Menu Wrapper-->
								<!--begin::Toolbar-->
								<div class="d-flex align-items-center py-3">
									<!--begin::Dropdown-->
									<div class="dropdown dropdown-inline" data-toggle="tooltip" title="Quick actions" data-placement="left">
										<a href="#" class="btn btn-dark font-weight-bold px-6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Create</a>
										<div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-md dropdown-menu-right">
											<!--begin::Navigation-->
											<ul class="navi navi-hover">
												<li class="navi-header font-weight-bold py-4">
													<span class="font-size-lg">Choose Label:</span>
													<i class="flaticon2-information icon-md text-muted" data-toggle="tooltip" data-placement="right" title="Click to learn more..."></i>
												</li>
												<li class="navi-separator mb-3 opacity-70"></li>
												<li class="navi-item">
													<a href="#" class="navi-link">
														<span class="navi-text">
															<span class="label label-xl label-inline label-light-success">Customer</span>
														</span>
													</a>
												</li>
												<li class="navi-item">
													<a href="#" class="navi-link">
														<span class="navi-text">
															<span class="label label-xl label-inline label-light-danger">Partner</span>
														</span>
													</a>
												</li>
												<li class="navi-item">
													<a href="#" class="navi-link">
														<span class="navi-text">
															<span class="label label-xl label-inline label-light-warning">Suplier</span>
														</span>
													</a>
												</li>
												<li class="navi-item">
													<a href="#" class="navi-link">
														<span class="navi-text">
															<span class="label label-xl label-inline label-light-primary">Member</span>
														</span>
													</a>
												</li>
												<li class="navi-item">
													<a href="#" class="navi-link">
														<span class="navi-text">
															<span class="label label-xl label-inline label-light-dark">Staff</span>
														</span>
													</a>
												</li>
												<li class="navi-separator mt-3 opacity-70"></li>
												<li class="navi-footer py-4">
													<a class="btn btn-clean font-weight-bold btn-sm" href="#">
													<i class="ki ki-plus icon-sm"></i>Add new</a>
												</li>
											</ul>
											<!--end::Navigation-->
										</div>
									</div>
									<!--end::Dropdown-->
								</div>
								<!--end::Toolbar-->
							</div>
							<!--end::Container-->
						</div>
						<!--end::Header Wrapper-->
					</div> --}}
                <!--end::Header-->
                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <!--begin::Subheader-->
                    <div class="subheader py-2 py-lg-6 subheader-transparent" id="kt_subheader">
                        <div
                            class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                            <!--begin::Info-->
                            <div class="d-flex align-items-center flex-wrap mr-1">
                                <!--begin::Page Heading-->
                                <div class="d-flex align-items-baseline flex-wrap mr-5">
                                    <!--begin::Page Title-->
                                    <h5 class="text-white font-weight-bold my-1 mr-5">Layout Builder</h5>
                                    <!--end::Page Title-->
                                    <!--begin::Breadcrumb-->
                                    <ul
                                        class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                                        <li class="breadcrumb-item">
                                            <a href="" class="text-muted">Features</a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="" class="text-muted">Layout Builder</a>
                                        </li>
                                    </ul>
                                    <!--end::Breadcrumb-->
                                </div>
                                <!--end::Page Heading-->
                            </div>
                            <!--end::Info-->
                            <!--begin::Toolbar-->
                            <div class="d-flex align-items-center">
                                <!--begin::Actions-->
                                <a href="#" class="btn btn-light-primary font-weight-bolder btn-sm">Actions</a>
                                <!--end::Actions-->
                                <!--begin::Dropdown-->
                                <div class="dropdown dropdown-inline" data-toggle="tooltip" title="Quick actions"
                                    data-placement="left">
                                    <a href="#" class="btn btn-icon" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right p-0 m-0">
                                        <!--begin::Navigation-->
                                        <ul class="navi navi-hover">
                                            <li class="navi-header font-weight-bold py-4">
                                                <span class="font-size-lg">Choose Label:</span>
                                                <i class="flaticon2-information icon-md text-muted"
                                                    data-toggle="tooltip" data-placement="right"
                                                    title="Click to learn more..."></i>
                                            </li>
                                            <li class="navi-separator mb-3 opacity-70"></li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-text">
                                                        <span
                                                            class="label label-xl label-inline label-light-success">Customer</span>
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-text">
                                                        <span
                                                            class="label label-xl label-inline label-light-danger">Partner</span>
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-text">
                                                        <span
                                                            class="label label-xl label-inline label-light-warning">Suplier</span>
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-text">
                                                        <span
                                                            class="label label-xl label-inline label-light-primary">Member</span>
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-text">
                                                        <span
                                                            class="label label-xl label-inline label-light-dark">Staff</span>
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="navi-separator mt-3 opacity-70"></li>
                                            <li class="navi-footer py-4">
                                                <a class="btn btn-clean font-weight-bold btn-sm" href="#">
                                                    <i class="ki ki-plus icon-sm"></i>Add new</a>
                                            </li>
                                        </ul>
                                        <!--end::Navigation-->
                                    </div>
                                </div>
                                <!--end::Dropdown-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                    </div>
                    <!--end::Subheader-->
                    <!--begin::Entry-->
                    <div class="d-flex flex-column-fluid">
                        <!--begin::Container-->
                        <div class="container-fluid">
                            <!--begin::Notice-->
                            <div class="alert alert-custom alert-white alert-shadow gutter-b" role="alert">
                                <div class="alert-icon alert-icon-top">
                                    <span class="svg-icon svg-icon-3x svg-icon-primary mt-4">
                                        <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Tools/Tools.svg-->

                                        <!--end::Svg Icon-->
                                    </span>
                                </div>
                                <div class="alert-text">
                                    <p>The layout builder is to assist your set and configure your preferred project
                                        layout specifications and preview it in real time. The configured layout options
                                        will be saved until you change or reset them. To use the layout builder, choose
                                        the layout options and click the
                                        <code>Preview</code>button to preview the changes and click the
                                        <code>Export</code>button to download the HTML template with its includable
                                        partials of this demo. In the downloaded folder the partials(header, footer,
                                        aside, topbar, etc) will be placed seperated from the base layout to allow you
                                        to integrate base layout into your application
                                    </p>
                                    <p>
                                        <span
                                            class="label label-inline label-pill label-danger label-rounded mr-2">NOTE:</span>The
                                        downloaded version does not include the assets folder since the layout builder's
                                        main purpose is to help to generate the final HTML code without hassle.
                                    </p>
                                </div>
                            </div>
                            <!--end::Notice-->
                            <!--begin::Card-->
                            <div class="card card-custom card-shadowless">
                                <!--begin::Header-->
                                <div class="card-header card-header-tabs-line">
                                    <ul class="nav nav-dark nav-bold nav-tabs nav-tabs-line" data-remember-tab="tab_id"
                                        role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kt_builder_page"
                                                role="tab">Page</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kt_builder_header"
                                                role="tab">Header</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kt_builder_subheader"
                                                role="tab">Subheader</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#kt_builder_content"
                                                role="tab">Content</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kt_builder_sidebar"
                                                role="tab">Sidebar</a>
                                        </li>
                                    </ul>
                                </div>
                                <!--end::Header-->
                                <!--begin::Form-->
                                <form class="form" method="POST" action="/metronic/index.php">
                                    <!--begin::Body-->
                                    <div class="card-body">
                                        <div class="tab-content pt-3">
                                            <div class="tab-pane" id="kt_builder_page">
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label text-lg-right">Page
                                                        Loader:</label>
                                                    <div class="col-lg-9 col-xl-4">
                                                        <select class="form-control form-control-solid"
                                                            name="builder[layout][page-loader][type]">
                                                            <option value="" selected="selected">Disabled</option>
                                                            <option value="default">Spinner</option>
                                                            <option value="spinner-message">Spinner &amp; Message
                                                            </option>
                                                            <option value="spinner-logo">Spinner &amp; Logo</option>
                                                        </select>
                                                        <div class="form-text text-muted">Select page loading indicator
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label text-lg-right">Display Page
                                                        Toolbar:</label>
                                                    <div class="col-lg-9 col-xl-4">
                                                        <input type="hidden" name="builder[layout][toolbar][display]"
                                                            value="false" />
                                                        <span class="switch switch-icon">
                                                            <label>
                                                                <input type="checkbox"
                                                                    name="builder[layout][toolbar][display]"
                                                                    value="true" />
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                        <div class="form-text text-muted">Display or hide the page's
                                                            right center toolbar(demos switcher, documentation and page
                                                            builder links)</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="kt_builder_header">
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label text-lg-right">Desktop Fixed
                                                        Header:</label>
                                                    <div class="col-lg-9 col-xl-4">
                                                        <input type="hidden"
                                                            name="builder[layout][header][self][fixed][desktop]"
                                                            value="false" />
                                                        <span class="switch switch-icon">
                                                            <label>
                                                                <input type="checkbox"
                                                                    name="builder[layout][header][self][fixed][desktop]"
                                                                    value="true" checked="checked" />
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                        <div class="form-text text-muted">Enable fixed header for
                                                            desktop mode</div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label text-lg-right">Mobile Fixed
                                                        Header:</label>
                                                    <div class="col-lg-9 col-xl-4">
                                                        <input type="hidden"
                                                            name="builder[layout][header][self][fixed][mobile]"
                                                            value="false" />
                                                        <span class="switch switch-icon">
                                                            <label>
                                                                <input type="checkbox"
                                                                    name="builder[layout][header][self][fixed][mobile]"
                                                                    value="true" checked="checked" />
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                        <div class="form-text text-muted">Enable fixed header for
                                                            mobile mode</div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label text-lg-right">Header
                                                        Width:</label>
                                                    <div class="col-lg-9 col-xl-4">
                                                        <select class="form-control form-control-solid"
                                                            name="builder[layout][header][self][width]">
                                                            <option value="fluid" selected="selected">Fluid</option>
                                                            <option value="fixed">Fixed</option>
                                                        </select>
                                                        <div class="form-text text-muted">Select header width type.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label text-lg-right">Header Menu
                                                        Arrows:</label>
                                                    <div class="col-lg-9 col-xl-4">
                                                        <input type="hidden"
                                                            name="builder[layout][header][menu][self][root-arrow]"
                                                            value="false" />
                                                        <span class="switch switch-icon">
                                                            <label>
                                                                <input type="checkbox"
                                                                    name="builder[layout][header][menu][self][root-arrow]"
                                                                    value="true" />
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                        <div class="form-text text-muted">Enable header menu root link
                                                            arrows</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="kt_builder_subheader">
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label text-lg-right">Display
                                                        Subheader:</label>
                                                    <div class="col-lg-9 col-xl-4">
                                                        <input type="hidden" name="builder[layout][subheader][display]"
                                                            value="false" />
                                                        <span class="switch switch-icon">
                                                            <label>
                                                                <input type="checkbox"
                                                                    name="builder[layout][subheader][display]"
                                                                    value="true" checked="checked" />
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                        <div class="form-text text-muted">Display subheader</div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label text-lg-right">Width:</label>
                                                    <div class="col-lg-9 col-xl-4">
                                                        <select class="form-control form-control-solid"
                                                            name="builder[layout][subheader][width]">
                                                            <option value="fluid">Fluid</option>
                                                            <option value="fixed" selected="selected">Fixed</option>
                                                        </select>
                                                        <div class="form-text text-muted">Select layout width type.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label text-lg-right">Subheader
                                                        Layout:</label>
                                                    <div class="col-lg-9 col-xl-4">
                                                        <select class="form-control form-control-solid"
                                                            name="builder[layout][subheader][layout]">
                                                            <option value="subheader-v1" selected="selected">Subheader
                                                                v1</option>
                                                            <option value="subheader-v2">Subheader v2</option>
                                                            <option value="subheader-v3">Subheader v3</option>
                                                            <option value="subheader-v4">Subheader v4</option>
                                                            <option value="subheader-v5">Subheader v5</option>
                                                            <option value="subheader-v6">Subheader v6</option>
                                                            <option value="subheader-v7">Subheader v7</option>
                                                        </select>
                                                        <div class="form-text text-muted">Select subheader layout</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane active" id="kt_builder_content">
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label text-lg-right">Width:</label>
                                                    <div class="col-lg-9 col-xl-4">
                                                        <select class="form-control form-control-solid"
                                                            name="builder[layout][content][width]">
                                                            <option value="fluid">Fluid</option>
                                                            <option value="fixed" selected="selected">Fixed</option>
                                                        </select>
                                                        <div class="form-text text-muted">Select layout width type.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="kt_builder_sidebar">
                                                <div class="form-group row">
                                                    <label
                                                        class="col-lg-3 col-form-label text-lg-right">Display:</label>
                                                    <div class="col-lg-9 col-xl-4">
                                                        <span class="switch switch-icon">
                                                            <input type="hidden"
                                                                name="builder[layout][sidebar][self][display]"
                                                                value="false" />
                                                            <label>
                                                                <input type="checkbox"
                                                                    name="builder[layout][sidebar][self][display]"
                                                                    value="true" />
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                        <div class="form-text text-muted">Display Sidebar panel</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Body-->
                                    <!--begin::Footer-->
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-9">
                                                <input type="hidden" id="tab_id" name="builder[tab][tab_id]" />
                                                <button type="submit" name="builder_submit" data-demo="demo6"
                                                    class="btn btn-primary font-weight-bold mr-2">Preview</button>
                                                <button type="submit" id="builder_export" data-demo="demo6"
                                                    class="btn btn-light font-weight-bold mr-2">Export</button>
                                                <button type="submit" name="builder_reset" data-demo="demo6"
                                                    class="btn btn-clean font-weight-bold">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Footer-->
                                </form>
                                <!--end::Form-->
                            </div>
                            <!--end::Card-->
                            <!--begin::Modal-->
                            <div class="modal fade kt-modal-purchase" id="kt-modal-purchase" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="exampleModalLabel">reCaptcha Verification
                                            </h3>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="form">
                                                <div class="form-group">
                                                    <script src="https://www.google.com/recaptcha/api.js"></script>
                                                    <div class="g-recaptcha"
                                                        data-sitekey="6Lf92jMUAAAAANk8wz68r73rA2uPGr4_e0gn96BL"></div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary"
                                                id="submit-verify">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Modal-->
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Entry-->
                </div>
                <!--end::Content-->
                <!--begin::Footer-->
                <div class="footer py-2 py-lg-0 my-5 d-flex flex-lg-column" id="kt_footer">
                    <!--begin::Container-->
                    <div
                        class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <!--begin::Copyright-->
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-muted font-weight-bold mr-2">2020 ©</span>
                            <a href="" target="_blank"
                                class="text-primary text-hover-primary">{{ env('APP_NAME') }}</a>
                        </div>
                        <!--end::Copyright-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Main-->
    <!-- begin::Notifications Panel-->
    <div id="kt_quick_notifications" class="offcanvas offcanvas-left p-10">
        <!--begin::Header-->
        <div class="offcanvas-header d-flex align-items-center justify-content-between mb-10">
            <h3 class="font-weight-bold m-0">Notifications
                <small class="text-muted font-size-sm ml-2">24 New</small>
            </h3>
            <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_notifications_close">
                <i class="ki ki-close icon-xs text-muted"></i>
            </a>
        </div>
        <!--end::Header-->
        <!--begin::Content-->
        <div class="offcanvas-content pr-5 mr-n5">
            <!--begin::Nav-->
            <div class="navi navi-icon-circle navi-spacer-x-0">
                <!--begin::Item-->
                <a href="#" class="navi-item">
                    <div class="navi-link rounded">
                        <div class="symbol symbol-50 symbol-circle mr-3">
                            <div class="symbol-label">
                                <i class="flaticon-bell text-success icon-lg"></i>
                            </div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold font-size-lg">5 new user generated report</div>
                            <div class="text-muted">Reports based on sales</div>
                        </div>
                    </div>
                </a>
                <!--end::Item-->
                <!--begin::Item-->
                <a href="#" class="navi-item">
                    <div class="navi-link rounded">
                        <div class="symbol symbol-50 symbol-circle mr-3">
                            <div class="symbol-label">
                                <i class="flaticon2-box text-danger icon-lg"></i>
                            </div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold font-size-lg">2 new items submited</div>
                            <div class="text-muted">by Grog John</div>
                        </div>
                    </div>
                </a>
                <!--end::Item-->
                <!--begin::Item-->
                <a href="#" class="navi-item">
                    <div class="navi-link rounded">
                        <div class="symbol symbol-50 symbol-circle mr-3">
                            <div class="symbol-label">
                                <i class="flaticon-psd text-primary icon-lg"></i>
                            </div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold font-size-lg">79 PSD files generated</div>
                            <div class="text-muted">Reports based on sales</div>
                        </div>
                    </div>
                </a>
                <!--end::Item-->
                <!--begin::Item-->
                <a href="#" class="navi-item">
                    <div class="navi-link rounded">
                        <div class="symbol symbol-50 symbol-circle mr-3">
                            <div class="symbol-label">
                                <i class="flaticon2-supermarket text-warning icon-lg"></i>
                            </div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold font-size-lg">$2900 worth producucts sold</div>
                            <div class="text-muted">Total 234 items</div>
                        </div>
                    </div>
                </a>
                <!--end::Item-->
                <!--begin::Item-->
                <a href="#" class="navi-item">
                    <div class="navi-link rounded">
                        <div class="symbol symbol-50 symbol-circle mr-3">
                            <div class="symbol-label">
                                <i class="flaticon-paper-plane-1 text-success icon-lg"></i>
                            </div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold font-size-lg">4.5h-avarage response time</div>
                            <div class="text-muted">Fostest is Barry</div>
                        </div>
                    </div>
                </a>
                <!--end::Item-->
                <!--begin::Item-->
                <a href="#" class="navi-item">
                    <div class="navi-link rounded">
                        <div class="symbol symbol-50 symbol-circle mr-3">
                            <div class="symbol-label">
                                <i class="flaticon-safe-shield-protection text-danger icon-lg"></i>
                            </div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold font-size-lg">3 Defence alerts</div>
                            <div class="text-muted">40% less alerts thar last week</div>
                        </div>
                    </div>
                </a>
                <!--end::Item-->
                <!--begin::Item-->
                <a href="#" class="navi-item">
                    <div class="navi-link rounded">
                        <div class="symbol symbol-50 symbol-circle mr-3">
                            <div class="symbol-label">
                                <i class="flaticon-notepad text-primary icon-lg"></i>
                            </div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold font-size-lg">Avarage 4 blog posts per author</div>
                            <div class="text-muted">Most posted 12 time</div>
                        </div>
                    </div>
                </a>
                <!--end::Item-->
                <!--begin::Item-->
                <a href="#" class="navi-item">
                    <div class="navi-link rounded">
                        <div class="symbol symbol-50 symbol-circle mr-3">
                            <div class="symbol-label">
                                <i class="flaticon-users-1 text-warning icon-lg"></i>
                            </div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold font-size-lg">16 authors joined last week</div>
                            <div class="text-muted">9 photodrapehrs, 7 designer</div>
                        </div>
                    </div>
                </a>
                <!--end::Item-->
                <!--begin::Item-->
                <a href="#" class="navi-item">
                    <div class="navi-link rounded">
                        <div class="symbol symbol-50 symbol-circle mr-3">
                            <div class="symbol-label">
                                <i class="flaticon2-box text-info icon-lg"></i>
                            </div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold font-size-lg">2 new items have been submited</div>
                            <div class="text-muted">by Grog John</div>
                        </div>
                    </div>
                </a>
                <!--end::Item-->
                <!--begin::Item-->
                <a href="#" class="navi-item">
                    <div class="navi-link rounded">
                        <div class="symbol symbol-50 symbol-circle mr-3">
                            <div class="symbol-label">
                                <i class="flaticon2-download text-success icon-lg"></i>
                            </div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold font-size-lg">2.8 GB-total downloads size</div>
                            <div class="text-muted">Mostly PSD end AL concepts</div>
                        </div>
                    </div>
                </a>
                <!--end::Item-->
                <!--begin::Item-->
                <a href="#" class="navi-item">
                    <div class="navi-link rounded">
                        <div class="symbol symbol-50 symbol-circle mr-3">
                            <div class="symbol-label">
                                <i class="flaticon2-supermarket text-danger icon-lg"></i>
                            </div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold font-size-lg">$2900 worth producucts sold</div>
                            <div class="text-muted">Total 234 items</div>
                        </div>
                    </div>
                </a>
                <!--end::Item-->
                <!--begin::Item-->
                <a href="#" class="navi-item">
                    <div class="navi-link rounded">
                        <div class="symbol symbol-50 symbol-circle mr-3">
                            <div class="symbol-label">
                                <i class="flaticon-bell text-primary icon-lg"></i>
                            </div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold font-size-lg">7 new user generated report</div>
                            <div class="text-muted">Reports based on sales</div>
                        </div>
                    </div>
                </a>
                <!--end::Item-->
                <!--begin::Item-->
                <a href="#" class="navi-item">
                    <div class="navi-link rounded">
                        <div class="symbol symbol-50 symbol-circle mr-3">
                            <div class="symbol-label">
                                <i class="flaticon-paper-plane-1 text-success icon-lg"></i>
                            </div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold font-size-lg">4.5h-avarage response time</div>
                            <div class="text-muted">Fostest is Barry</div>
                        </div>
                    </div>
                </a>
                <!--end::Item-->
            </div>
            <!--end::Nav-->
        </div>
        <!--end::Content-->
    </div>
    <!-- end::Notifications Panel-->
    <!--begin::Quick Actions Panel-->
    <div id="kt_quick_actions" class="offcanvas offcanvas-left p-10">
        <!--begin::Header-->
        <div class="offcanvas-header d-flex align-items-center justify-content-between pb-10">
            <h3 class="font-weight-bold m-0">Quick Actions
                <small class="text-muted font-size-sm ml-2">finance &amp; reports</small>
            </h3>
            <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_actions_close">
                <i class="ki ki-close icon-xs text-muted"></i>
            </a>
        </div>
        <!--end::Header-->
        <!--begin::Content-->
        <div class="offcanvas-content pr-5 mr-n5">
            <div class="row gutter-b">
                <!--begin::Item-->
                <div class="col-6">
                    <a href="#" class="btn btn-block btn-light btn-hover-primary text-dark-50 text-center py-10 px-5">
                        <span class="svg-icon svg-icon-3x svg-icon-primary m-0">
                            <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Shopping/Euro.svg-->

                            <!--end::Svg Icon-->
                        </span>
                        <span class="d-block font-weight-bold font-size-h6 mt-2">Accounting</span>
                    </a>
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="col-6">
                    <a href="#" class="btn btn-block btn-light btn-hover-primary text-dark-50 text-center py-10 px-5">
                        <span class="svg-icon svg-icon-3x svg-icon-primary m-0">
                            <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Communication/Mail-attachment.svg-->

                            <!--end::Svg Icon-->
                        </span>
                        <span class="d-block font-weight-bold font-size-h6 mt-2">Members</span>
                    </a>
                </div>
                <!--end::Item-->
            </div>
            <div class="row gutter-b">
                <!--begin::Item-->
                <div class="col-6">
                    <a href="#" class="btn btn-block btn-light btn-hover-primary text-dark-50 text-center py-10 px-5">
                        <span class="svg-icon svg-icon-3x svg-icon-primary m-0">
                            <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Shopping/Box2.svg-->

                            <!--end::Svg Icon-->
                        </span>
                        <span class="d-block font-weight-bold font-size-h6 mt-2">Projects</span>
                    </a>
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="col-6">
                    <a href="#" class="btn btn-block btn-light btn-hover-primary text-dark-50 text-center py-10 px-5">
                        <span class="svg-icon svg-icon-3x svg-icon-primary m-0">
                            <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Communication/Group.svg-->

                            <!--end::Svg Icon-->
                        </span>
                        <span class="d-block font-weight-bold font-size-h6 mt-2">Customers</span>
                    </a>
                </div>
                <!--end::Item-->
            </div>
            <div class="row gutter-b">
                <!--begin::Item-->
                <div class="col-6">
                    <a href="#" class="btn btn-block btn-light btn-hover-primary text-dark-50 text-center py-10 px-5">
                        <span class="svg-icon svg-icon-3x svg-icon-primary m-0">
                            <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Shopping/Chart-bar1.svg-->

                            <!--end::Svg Icon-->
                        </span>
                        <span class="d-block font-weight-bold font-size-h6 mt-2">Email</span>
                    </a>
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="col-6">
                    <a href="#" class="btn btn-block btn-light btn-hover-primary text-dark-50 text-center py-10 px-5">
                        <span class="svg-icon svg-icon-3x svg-icon-primary m-0">
                            <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Design/Color-profile.svg-->

                            <!--end::Svg Icon-->
                        </span>
                        <span class="d-block font-weight-bold font-size-h6 mt-2">Settings</span>
                    </a>
                </div>
                <!--end::Item-->
            </div>
            <div class="row">
                <!--begin::Item-->
                <div class="col-6">
                    <a href="#" class="btn btn-block btn-light btn-hover-primary text-dark-50 text-center py-10 px-5">
                        <span class="svg-icon svg-icon-3x svg-icon-primary m-0">
                            <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Shopping/Euro.svg-->

                            <!--end::Svg Icon-->
                        </span>
                        <span class="d-block font-weight-bold font-size-h6 mt-2">Orders</span>
                    </a>
                </div>
                <!--end::Item-->
            </div>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Quick Actions Panel-->
    <!-- begin::User Panel-->
    <div id="kt_quick_user" class="offcanvas offcanvas-left p-10">
        <!--begin::Header-->
        <div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
            <h3 class="font-weight-bold m-0">User Profile
                <small class="text-muted font-size-sm ml-2">12 messages</small>
            </h3>
            <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close">
                <i class="ki ki-close icon-xs text-muted"></i>
            </a>
        </div>
        <!--end::Header-->
        <!--begin::Content-->
        <div class="offcanvas-content pr-5 mr-n5">
            <!--begin::Header-->
            <div class="d-flex align-items-center mt-5">
                <div class="symbol symbol-100 mr-5">
                    {{-- <div class="symbol-label" style="background-image:url('{{asset('metronic/demo6/dist/assets/media/users/300_21.jpg')}})"></div> --}}
                    <i class="symbol-badge bg-success"></i>
                </div>
                <div class="d-flex flex-column">
                    <a href="#" class="font-weight-bold font-size-h5 text-primary text-hover-primary">James Jones</a>
                    <div class="text-muted mt-1">Application Developer</div>
                    <div class="navi mt-2">
                        <a href="#" class="navi-item">
                            <span class="navi-link p-0 pb-2">
                                <span class="navi-icon mr-1">
                                    <span class="svg-icon svg-icon-lg svg-icon-primary">
                                        <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Communication/Mail-notification.svg-->

                                        <!--end::Svg Icon-->
                                    </span>
                                </span>
                                <span class="navi-text text-muted text-hover-primary">jm@softplus.com</span>
                            </span>
                        </a>
                        <a href="#" class="btn btn-sm btn-light-primary font-weight-bolder py-2 px-5">Sign Out</a>
                    </div>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Separator-->
            <div class="separator separator-dashed mt-8 mb-5"></div>
            <!--end::Separator-->
            <!--begin::Nav-->
            <div class="navi navi-spacer-x-0 p-0">
                <!--begin::Item-->
                <a href="/metronic/demo6/custom/apps/user/profile-1/personal-information.html" class="navi-item">
                    <div class="navi-link">
                        <div class="symbol symbol-40 bg-light mr-3">
                            <div class="symbol-label">
                                <span class="svg-icon svg-icon-md svg-icon-success">
                                    <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/General/Notification2.svg-->

                                    <!--end::Svg Icon-->
                                </span>
                            </div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold">My Profile</div>
                            <div class="text-muted">Account settings and more
                                <span class="label label-light-danger label-inline font-weight-bold">update</span>
                            </div>
                        </div>
                    </div>
                </a>
                <!--end:Item-->
                <!--begin::Item-->
                <a href="/metronic/demo6/custom/apps/user/profile-3.html" class="navi-item">
                    <div class="navi-link">
                        <div class="symbol symbol-40 bg-light mr-3">
                            <div class="symbol-label">
                                <span class="svg-icon svg-icon-md svg-icon-warning">
                                    <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Shopping/Chart-bar1.svg-->

                                    <!--end::Svg Icon-->
                                </span>
                            </div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold">My Messages</div>
                            <div class="text-muted">Inbox and tasks</div>
                        </div>
                    </div>
                </a>
                <!--end:Item-->
                <!--begin::Item-->
                <a href="/metronic/demo6/custom/apps/user/profile-2.html" class="navi-item">
                    <div class="navi-link">
                        <div class="symbol symbol-40 bg-light mr-3">
                            <div class="symbol-label">
                                <span class="svg-icon svg-icon-md svg-icon-danger">
                                    <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Files/Selected-file.svg-->

                                    <!--end::Svg Icon-->
                                </span>
                            </div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold">My Activities</div>
                            <div class="text-muted">Logs and notifications</div>
                        </div>
                    </div>
                </a>
                <!--end:Item-->
                <!--begin::Item-->
                <a href="/metronic/demo6/custom/apps/userprofile-1/overview.html" class="navi-item">
                    <div class="navi-link">
                        <div class="symbol symbol-40 bg-light mr-3">
                            <div class="symbol-label">
                                <span class="svg-icon svg-icon-md svg-icon-primary">
                                    <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Communication/Mail-opened.svg-->

                                    <!--end::Svg Icon-->
                                </span>
                            </div>
                        </div>
                        <div class="navi-text">
                            <div class="font-weight-bold">My Tasks</div>
                            <div class="text-muted">latest tasks and projects</div>
                        </div>
                    </div>
                </a>
                <!--end:Item-->
            </div>
            <!--end::Nav-->
            <!--begin::Separator-->
            <div class="separator separator-dashed my-7"></div>
            <!--end::Separator-->
            <!--begin::Notifications-->
            <div>
                <!--begin:Heading-->
                <h5 class="mb-5">Recent Notifications</h5>
                <!--end:Heading-->
                <!--begin::Item-->
                <div class="d-flex align-items-center bg-light-warning rounded p-5 gutter-b">
                    <span class="svg-icon svg-icon-warning mr-5">
                        <span class="svg-icon svg-icon-lg">
                            <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Home/Library.svg-->

                            <!--end::Svg Icon-->
                        </span>
                    </span>
                    <div class="d-flex flex-column flex-grow-1 mr-2">
                        <a href="#"
                            class="font-weight-normal text-primary text-hover-primary font-size-lg mb-1">Another
                            purpose persuade</a>
                        <span class="text-muted font-size-sm">Due in 2 Days</span>
                    </div>
                    <span class="font-weight-bolder text-warning py-1 font-size-lg">+28%</span>
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex align-items-center bg-light-success rounded p-5 gutter-b">
                    <span class="svg-icon svg-icon-success mr-5">
                        <span class="svg-icon svg-icon-lg">
                            <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Communication/Write.svg-->

                            <!--end::Svg Icon-->
                        </span>
                    </span>
                    <div class="d-flex flex-column flex-grow-1 mr-2">
                        <a href="#" class="font-weight-normal text-primary text-hover-primary font-size-lg mb-1">Would
                            be to people</a>
                        <span class="text-muted font-size-sm">Due in 2 Days</span>
                    </div>
                    <span class="font-weight-bolder text-success py-1 font-size-lg">+50%</span>
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex align-items-center bg-light-danger rounded p-5 gutter-b">
                    <span class="svg-icon svg-icon-danger mr-5">
                        <span class="svg-icon svg-icon-lg">
                            <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Communication/Group-chat.svg-->

                            <!--end::Svg Icon-->
                        </span>
                    </span>
                    <div class="d-flex flex-column flex-grow-1 mr-2">
                        <a href="#"
                            class="font-weight-normel text-primary text-hover-primary font-size-lg mb-1">Purpose would
                            be to persuade</a>
                        <span class="text-muted font-size-sm">Due in 2 Days</span>
                    </div>
                    <span class="font-weight-bolder text-danger py-1 font-size-lg">-27%</span>
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex align-items-center bg-light-info rounded p-5">
                    <span class="svg-icon svg-icon-info mr-5">
                        <span class="svg-icon svg-icon-lg">
                            <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/General/Attachment2.svg-->

                            <!--end::Svg Icon-->
                        </span>
                    </span>
                    <div class="d-flex flex-column flex-grow-1 mr-2">
                        <a href="#" class="font-weight-normel text-primary text-hover-primary font-size-lg mb-1">The
                            best product</a>
                        <span class="text-muted font-size-sm">Due in 2 Days</span>
                    </div>
                    <span class="font-weight-bolder text-info py-1 font-size-lg">+8%</span>
                </div>
                <!--end::Item-->
            </div>
            <!--end::Notifications-->
        </div>
        <!--end::Content-->
    </div>
    <!-- end::User Panel-->
    <!--begin::Quick Panel-->
    <div id="kt_quick_panel" class="offcanvas offcanvas-left pt-5 pb-10">
        <!--begin::Header-->
        <div class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-5">
            <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-primary flex-grow-1 px-10"
                role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#kt_quick_panel_logs">Audit Logs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kt_quick_panel_notifications">Notifications</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kt_quick_panel_settings">Settings</a>
                </li>
            </ul>
            <div class="offcanvas-close mt-n1 pr-5">
                <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_panel_close">
                    <i class="ki ki-close icon-xs text-muted"></i>
                </a>
            </div>
        </div>
        <!--end::Header-->
        <!--begin::Content-->
        <div class="offcanvas-content px-10">
            <div class="tab-content">
                <!--begin::Tabpane-->
                <div class="tab-pane fade show pt-3 pr-5 mr-n5 active" id="kt_quick_panel_logs" role="tabpanel">
                    <!--begin::Section-->
                    <div class="mb-15">
                        <h5 class="font-weight-bold mb-5">System Messages</h5>
                        <!--begin: Item-->
                        <div class="d-flex align-items-center flex-wrap mb-5">
                            <div class="symbol symbol-50 symbol-light mr-5">
                                <span class="symbol-label">
                                    <img src="{{ asset('/metronic/demo6/dist/assets/media/svg/misc/006-plurk.svg') }}"
                                        class="h-50 align-self-center" alt="" />
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <a href="#"
                                    class="font-weight-bolder text-primary text-hover-primary font-size-lg mb-1">Top
                                    Authors</a>
                                <span class="text-muted font-weight-bold">Most Successful Fellas</span>
                            </div>
                            <span
                                class="btn btn-sm btn-light font-weight-bolder py-1 my-lg-0 my-2 text-dark-50">+82$</span>
                        </div>
                        <!--end: Item-->
                        <!--begin: Item-->
                        <div class="d-flex align-items-center flex-wrap mb-5">
                            <div class="symbol symbol-50 symbol-light mr-5">
                                <span class="symbol-label">
                                    <img src="{{ asset('/metronic/demo6/dist/assets/media/svg/misc/015-telegram.svg') }}"
                                        class="h-50 align-self-center" alt="" />
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <a href="#"
                                    class="font-weight-bolder text-primary text-hover-primary font-size-lg mb-1">Popular
                                    Authors</a>
                                <span class="text-muted font-weight-bold">Most Successful Fellas</span>
                            </div>
                            <span
                                class="btn btn-sm btn-light font-weight-bolder my-lg-0 my-2 py-1 text-dark-50">+280$</span>
                        </div>
                        <!--end: Item-->
                        <!--begin: Item-->
                        <div class="d-flex align-items-center flex-wrap mb-5">
                            <div class="symbol symbol-50 symbol-light mr-5">
                                <span class="symbol-label">
                                    <img src="{{ asset('/metronic/demo6/dist/assets/media/svg/misc/003-puzzle.svg') }}"
                                        class="h-50 align-self-center" alt="" />
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <a href="#"
                                    class="font-weight-bolder text-primary text-hover-primary font-size-lg mb-1">New
                                    Users</a>
                                <span class="text-muted font-weight-bold">Most Successful Fellas</span>
                            </div>
                            <span
                                class="btn btn-sm btn-light font-weight-bolder my-lg-0 my-2 py-1 text-dark-50">+4500$</span>
                        </div>
                        <!--end: Item-->
                        <!--begin: Item-->
                        <div class="d-flex align-items-center flex-wrap mb-5">
                            <div class="symbol symbol-50 symbol-light mr-5">
                                <span class="symbol-label">
                                    <img src="{{ asset('/metronic/demo6/dist/assets/media/svg/misc/005-bebo.svg') }}"
                                        class="h-50 align-self-center" alt="" />
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <a href="#"
                                    class="font-weight-bolder text-primary text-hover-primary font-size-lg mb-1">Active
                                    Customers</a>
                                <span class="text-muted font-weight-bold">Most Successful Fellas</span>
                            </div>
                            <span
                                class="btn btn-sm btn-light font-weight-bolder my-lg-0 my-2 py-1 text-dark-50">+4500$</span>
                        </div>
                        <!--end: Item-->
                        <!--begin: Item-->
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="symbol symbol-50 symbol-light mr-5">
                                <span class="symbol-label">
                                    <img src="{{ asset('/metronic/demo6/dist/assets/media/svg/misc/014-kickstarter.svg') }}"
                                        class="h-50 align-self-center" alt="" />
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <a href="#"
                                    class="font-weight-bolder text-primary text-hover-primary font-size-lg mb-1">Bestseller
                                    Theme</a>
                                <span class="text-muted font-weight-bold">Most Successful Fellas</span>
                            </div>
                            <span
                                class="btn btn-sm btn-light font-weight-bolder my-lg-0 my-2 py-1 text-dark-50">+4500$</span>
                        </div>
                        <!--end: Item-->
                    </div>
                    <!--end::Section-->
                    <!--begin::Section-->
                    <div class="mb-5">
                        <h5 class="font-weight-bold mb-5">Notifications</h5>
                        <!--begin: Item-->
                        <div class="d-flex align-items-center bg-light-warning rounded p-5 mb-5">
                            <span class="svg-icon svg-icon-warning mr-5">
                                <span class="svg-icon svg-icon-lg">
                                    <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Home/Library.svg-->

                                    <!--end::Svg Icon-->
                                </span>
                            </span>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <a href="#"
                                    class="font-weight-normal text-primary text-hover-primary font-size-lg mb-1">Another
                                    purpose persuade</a>
                                <span class="text-muted font-size-sm">Due in 2 Days</span>
                            </div>
                            <span class="font-weight-bolder text-warning py-1 font-size-lg">+28%</span>
                        </div>
                        <!--end: Item-->
                        <!--begin: Item-->
                        <div class="d-flex align-items-center bg-light-success rounded p-5 mb-5">
                            <span class="svg-icon svg-icon-success mr-5">
                                <span class="svg-icon svg-icon-lg">
                                    <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Communication/Write.svg-->

                                    <!--end::Svg Icon-->
                                </span>
                            </span>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <a href="#"
                                    class="font-weight-normal text-primary text-hover-primary font-size-lg mb-1">Would
                                    be to people</a>
                                <span class="text-muted font-size-sm">Due in 2 Days</span>
                            </div>
                            <span class="font-weight-bolder text-success py-1 font-size-lg">+50%</span>
                        </div>
                        <!--end: Item-->
                        <!--begin: Item-->
                        <div class="d-flex align-items-center bg-light-danger rounded p-5 mb-5">
                            <span class="svg-icon svg-icon-danger mr-5">
                                <span class="svg-icon svg-icon-lg">
                                    <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Communication/Group-chat.svg-->

                                    <!--end::Svg Icon-->
                                </span>
                            </span>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <a href="#"
                                    class="font-weight-normel text-primary text-hover-primary font-size-lg mb-1">Purpose
                                    would be to persuade</a>
                                <span class="text-muted font-size-sm">Due in 2 Days</span>
                            </div>
                            <span class="font-weight-bolder text-danger py-1 font-size-lg">-27%</span>
                        </div>
                        <!--end: Item-->
                        <!--begin: Item-->
                        <div class="d-flex align-items-center bg-light-info rounded p-5">
                            <span class="svg-icon svg-icon-info mr-5">
                                <span class="svg-icon svg-icon-lg">
                                    <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/General/Attachment2.svg-->

                                    <!--end::Svg Icon-->
                                </span>
                            </span>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <a href="#"
                                    class="font-weight-normel text-primary text-hover-primary font-size-lg mb-1">The
                                    best product</a>
                                <span class="text-muted font-size-sm">Due in 2 Days</span>
                            </div>
                            <span class="font-weight-bolder text-info py-1 font-size-lg">+8%</span>
                        </div>
                        <!--end: Item-->
                    </div>
                    <!--end::Section-->
                </div>
                <!--end::Tabpane-->
                <!--begin::Tabpane-->
                <div class="tab-pane fade pt-2 pr-5 mr-n5" id="kt_quick_panel_notifications" role="tabpanel">
                    <!--begin::Nav-->
                    <div class="navi navi-icon-circle navi-spacer-x-0">
                        <!--begin::Item-->
                        <a href="#" class="navi-item">
                            <div class="navi-link rounded">
                                <div class="symbol symbol-50 mr-3">
                                    <div class="symbol-label">
                                        <i class="flaticon-bell text-success icon-lg"></i>
                                    </div>
                                </div>
                                <div class="navi-text">
                                    <div class="font-weight-bold font-size-lg">5 new user generated report</div>
                                    <div class="text-muted">Reports based on sales</div>
                                </div>
                            </div>
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="#" class="navi-item">
                            <div class="navi-link rounded">
                                <div class="symbol symbol-50 mr-3">
                                    <div class="symbol-label">
                                        <i class="flaticon2-box text-danger icon-lg"></i>
                                    </div>
                                </div>
                                <div class="navi-text">
                                    <div class="font-weight-bold font-size-lg">2 new items submited</div>
                                    <div class="text-muted">by Grog John</div>
                                </div>
                            </div>
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="#" class="navi-item">
                            <div class="navi-link rounded">
                                <div class="symbol symbol-50 mr-3">
                                    <div class="symbol-label">
                                        <i class="flaticon-psd text-primary icon-lg"></i>
                                    </div>
                                </div>
                                <div class="navi-text">
                                    <div class="font-weight-bold font-size-lg">79 PSD files generated</div>
                                    <div class="text-muted">Reports based on sales</div>
                                </div>
                            </div>
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="#" class="navi-item">
                            <div class="navi-link rounded">
                                <div class="symbol symbol-50 mr-3">
                                    <div class="symbol-label">
                                        <i class="flaticon2-supermarket text-warning icon-lg"></i>
                                    </div>
                                </div>
                                <div class="navi-text">
                                    <div class="font-weight-bold font-size-lg">$2900 worth producucts sold</div>
                                    <div class="text-muted">Total 234 items</div>
                                </div>
                            </div>
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="#" class="navi-item">
                            <div class="navi-link rounded">
                                <div class="symbol symbol-50 mr-3">
                                    <div class="symbol-label">
                                        <i class="flaticon-paper-plane-1 text-success icon-lg"></i>
                                    </div>
                                </div>
                                <div class="navi-text">
                                    <div class="font-weight-bold font-size-lg">4.5h-avarage response time</div>
                                    <div class="text-muted">Fostest is Barry</div>
                                </div>
                            </div>
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="#" class="navi-item">
                            <div class="navi-link rounded">
                                <div class="symbol symbol-50 mr-3">
                                    <div class="symbol-label">
                                        <i class="flaticon-safe-shield-protection text-danger icon-lg"></i>
                                    </div>
                                </div>
                                <div class="navi-text">
                                    <div class="font-weight-bold font-size-lg">3 Defence alerts</div>
                                    <div class="text-muted">40% less alerts thar last week</div>
                                </div>
                            </div>
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="#" class="navi-item">
                            <div class="navi-link rounded">
                                <div class="symbol symbol-50 mr-3">
                                    <div class="symbol-label">
                                        <i class="flaticon-notepad text-primary icon-lg"></i>
                                    </div>
                                </div>
                                <div class="navi-text">
                                    <div class="font-weight-bold font-size-lg">Avarage 4 blog posts per author</div>
                                    <div class="text-muted">Most posted 12 time</div>
                                </div>
                            </div>
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="#" class="navi-item">
                            <div class="navi-link rounded">
                                <div class="symbol symbol-50 mr-3">
                                    <div class="symbol-label">
                                        <i class="flaticon-users-1 text-warning icon-lg"></i>
                                    </div>
                                </div>
                                <div class="navi-text">
                                    <div class="font-weight-bold font-size-lg">16 authors joined last week</div>
                                    <div class="text-muted">9 photodrapehrs, 7 designer</div>
                                </div>
                            </div>
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="#" class="navi-item">
                            <div class="navi-link rounded">
                                <div class="symbol symbol-50 mr-3">
                                    <div class="symbol-label">
                                        <i class="flaticon2-box text-info icon-lg"></i>
                                    </div>
                                </div>
                                <div class="navi-text">
                                    <div class="font-weight-bold font-size-lg">2 new items have been submited</div>
                                    <div class="text-muted">by Grog John</div>
                                </div>
                            </div>
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="#" class="navi-item">
                            <div class="navi-link rounded">
                                <div class="symbol symbol-50 mr-3">
                                    <div class="symbol-label">
                                        <i class="flaticon2-download text-success icon-lg"></i>
                                    </div>
                                </div>
                                <div class="navi-text">
                                    <div class="font-weight-bold font-size-lg">2.8 GB-total downloads size</div>
                                    <div class="text-muted">Mostly PSD end AL concepts</div>
                                </div>
                            </div>
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="#" class="navi-item">
                            <div class="navi-link rounded">
                                <div class="symbol symbol-50 mr-3">
                                    <div class="symbol-label">
                                        <i class="flaticon2-supermarket text-danger icon-lg"></i>
                                    </div>
                                </div>
                                <div class="navi-text">
                                    <div class="font-weight-bold font-size-lg">$2900 worth producucts sold</div>
                                    <div class="text-muted">Total 234 items</div>
                                </div>
                            </div>
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="#" class="navi-item">
                            <div class="navi-link rounded">
                                <div class="symbol symbol-50 mr-3">
                                    <div class="symbol-label">
                                        <i class="flaticon-bell text-primary icon-lg"></i>
                                    </div>
                                </div>
                                <div class="navi-text">
                                    <div class="font-weight-bold font-size-lg">7 new user generated report</div>
                                    <div class="text-muted">Reports based on sales</div>
                                </div>
                            </div>
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="#" class="navi-item">
                            <div class="navi-link rounded">
                                <div class="symbol symbol-50 mr-3">
                                    <div class="symbol-label">
                                        <i class="flaticon-paper-plane-1 text-success icon-lg"></i>
                                    </div>
                                </div>
                                <div class="navi-text">
                                    <div class="font-weight-bold font-size-lg">4.5h-avarage response time</div>
                                    <div class="text-muted">Fostest is Barry</div>
                                </div>
                            </div>
                        </a>
                        <!--end::Item-->
                    </div>
                    <!--end::Nav-->
                </div>
                <!--end::Tabpane-->
                <!--begin::Tabpane-->
                <div class="tab-pane fade pt-3 pr-5 mr-n5" id="kt_quick_panel_settings" role="tabpanel">
                    <form class="form">
                        <!--begin::Section-->
                        <div>
                            <h5 class="font-weight-bold mb-3">Customer Care</h5>
                            <div class="form-group mb-0 row align-items-center">
                                <label class="col-8 col-form-label">Enable Notifications:</label>
                                <div class="col-4 d-flex justify-content-end">
                                    <span class="switch switch-success switch-sm">
                                        <label>
                                            <input type="checkbox" checked="checked" name="select" />
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group mb-0 row align-items-center">
                                <label class="col-8 col-form-label">Enable Case Tracking:</label>
                                <div class="col-4 d-flex justify-content-end">
                                    <span class="switch switch-success switch-sm">
                                        <label>
                                            <input type="checkbox" name="quick_panel_notifications_2" />
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group mb-0 row align-items-center">
                                <label class="col-8 col-form-label">Support Portal:</label>
                                <div class="col-4 d-flex justify-content-end">
                                    <span class="switch switch-success switch-sm">
                                        <label>
                                            <input type="checkbox" checked="checked" name="select" />
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--end::Section-->
                        <div class="separator separator-dashed my-6"></div>
                        <!--begin::Section-->
                        <div class="pt-2">
                            <h5 class="font-weight-bold mb-3">Reports</h5>
                            <div class="form-group mb-0 row align-items-center">
                                <label class="col-8 col-form-label">Generate Reports:</label>
                                <div class="col-4 d-flex justify-content-end">
                                    <span class="switch switch-sm switch-danger">
                                        <label>
                                            <input type="checkbox" checked="checked" name="select" />
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group mb-0 row align-items-center">
                                <label class="col-8 col-form-label">Enable Report Export:</label>
                                <div class="col-4 d-flex justify-content-end">
                                    <span class="switch switch-sm switch-danger">
                                        <label>
                                            <input type="checkbox" name="select" />
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group mb-0 row align-items-center">
                                <label class="col-8 col-form-label">Allow Data Collection:</label>
                                <div class="col-4 d-flex justify-content-end">
                                    <span class="switch switch-sm switch-danger">
                                        <label>
                                            <input type="checkbox" checked="checked" name="select" />
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--end::Section-->
                        <div class="separator separator-dashed my-6"></div>
                        <!--begin::Section-->
                        <div class="pt-2">
                            <h5 class="font-weight-bold mb-3">Memebers</h5>
                            <div class="form-group mb-0 row align-items-center">
                                <label class="col-8 col-form-label">Enable Member singup:</label>
                                <div class="col-4 d-flex justify-content-end">
                                    <span class="switch switch-sm switch-primary">
                                        <label>
                                            <input type="checkbox" checked="checked" name="select" />
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group mb-0 row align-items-center">
                                <label class="col-8 col-form-label">Allow User Feedbacks:</label>
                                <div class="col-4 d-flex justify-content-end">
                                    <span class="switch switch-sm switch-primary">
                                        <label>
                                            <input type="checkbox" name="select" />
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group mb-0 row align-items-center">
                                <label class="col-8 col-form-label">Enable Customer Portal:</label>
                                <div class="col-4 d-flex justify-content-end">
                                    <span class="switch switch-sm switch-primary">
                                        <label>
                                            <input type="checkbox" checked="checked" name="select" />
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--end::Section-->
                    </form>
                </div>
                <!--end::Tabpane-->
            </div>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Quick Panel-->
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop">
        <span class="svg-icon">
            <!--begin::Svg Icon | /metronic/demo6/dist/assets/media/svg/icons/Navigation/Up-2.svg-->

            <!--end::Svg Icon-->
        </span>
    </div>
    <!--end::Scrolltop-->
    <!--begin::Sticky Toolbar-->
    <ul class="sticky-toolbar nav flex-column pl-2 pr-2 pt-3 pb-3 mt-4">
        <!--begin::Item-->
        <li class="nav-item mb-2" id="kt_demo_panel_toggle" data-toggle="tooltip" title="Check out more demos"
            data-placement="right">
            <a class="btn btn-sm btn-icon btn-bg-light btn-icon-success btn-hover-success" href="#">
                <i class="flaticon2-drop"></i>
            </a>
        </li>
        <!--end::Item-->
    </ul>
    <!--end::Sticky Toolbar-->
    <!--begin::Demo Panel-->
    <div id="kt_demo_panel" class="offcanvas offcanvas-right p-10">
        <!--begin::Header-->
        <div class="offcanvas-header d-flex align-items-center justify-content-between pb-7">
            <h4 class="font-weight-bold m-0">Select A Demo</h4>
            <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_demo_panel_close">
                <i class="ki ki-close icon-xs text-muted"></i>
            </a>
        </div>
        <!--end::Header-->
        <!--begin::Content-->
        <div class="offcanvas-content">
            <!--begin::Wrapper-->
            <div class="offcanvas-wrapper mb-5 scroll-pull">

            </div>
            <!--end::Wrapper-->
            <!--begin::Purchase-->
            <div class="offcanvas-footer">
                <a href="https://1.envato.market/EA4JP" target="_blank"
                    class="btn btn-block btn-danger btn-shadow font-weight-bolder text-uppercase">Buy Metronic Now!</a>
            </div>
            <!--end::Purchase-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Demo Panel-->
    <script>
        var KTAppSettings;
    </script>

    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="{{ asset('/metronic/demo6/dist/assets/plugins/global/plugins.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/metronic/demo6/dist/assets/plugins/custom/prismjs/prismjs.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('/metronic/demo6/dist/assets/js/scripts.bundle.js?v=7.0.6') }}"></script>
    <!--end::Global Theme Bundle-->
    <!--begin::Page Scripts(used by this page)-->
    <script src="{{ asset('/metronic/demo6/dist/assets/js/pages/builder.js?v=7.0.6') }}"></script>
    <!--end::Page Scripts-->
</body>
<!--end::Body-->

</html>
