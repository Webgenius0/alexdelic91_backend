<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{ asset('backend/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('backend/js/scripts.bundle.js') }}"></script>
<!--end::Global Javascript Bundle-->

<!--begin::Vendors Javascript(used for this page only)-->
<script src="{{ asset('backend/plugins/custom/leaflet/leaflet.bundle.js') }}"></script>
<script src="{{ asset('backend/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<!--end::Vendors Javascript-->

<!--begin::Custom Javascript(used for this page only)-->
<script src="{{ asset('backend/js/widgets.bundle.js') }}"></script>
<script src="{{ asset('backend/js/custom/widgets.js') }}"></script>
<script src="{{ asset('backend/js/custom/apps/chat/chat.js') }}"></script>
<script src="{{ asset('backend/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
<script src="{{ asset('backend/js/custom/utilities/modals/create-project/type.js') }}"></script>
<script src="{{ asset('backend/js/custom/utilities/modals/create-project/budget.js') }}"></script>
<script src="{{ asset('backend/js/custom/utilities/modals/create-project/settings.js') }}"></script>
<script src="{{ asset('backend/js/custom/utilities/modals/create-project/team.js') }}"></script>
<script src="{{ asset('backend/js/custom/utilities/modals/create-project/targets.js') }}"></script>
<script src="{{ asset('backend/js/custom/utilities/modals/create-project/files.js') }}"></script>
<script src="{{ asset('backend/js/custom/utilities/modals/create-project/complete.js') }}"></script>
<script src="{{ asset('backend/js/custom/utilities/modals/create-project/main.js') }}"></script>
<script src="{{ asset('backend/js/custom/utilities/modals/select-location.js') }}"></script>
<script src="{{ asset('backend/js/custom/utilities/modals/create-app.js') }}"></script>
<script src="{{ asset('backend/js/custom/utilities/modals/users-search.js') }}"></script>
<!--end::Custom Javascript-->

{{-- Main-Resourse start --}}
<script src="{{ asset('backend/js/jquery-3.7.1.js') }}" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
<script src="{{ asset('backend/js/datatables.min.js') }}"></script>
<script src="{{ asset('backend/js/sweetalert2@11.js') }}"></script>
<script src="{{ asset('backend/js/toastr.min.js') }}"></script>
<script src="{{ asset('backend/js/dropify.min.js') }}"></script>
<script src="{{ asset('backend/js/ckeditor.js') }}"></script>
{{-- Main-Resourse end --}}


{{-- toastr start --}}
<script>
    $(document).ready(function() {
        toastr.options.timeOut = 10000;
        toastr.options.positionClass = 'toast-top-right';

        @if (Session::has('t-success'))
            toastr.options = {
                'closeButton': true,
                'debug': false,
                'newestOnTop': true,
                'progressBar': true,
                'positionClass': 'toast-top-right',
                'preventDuplicates': false,
                'showDuration': '1000',
                'hideDuration': '1000',
                'timeOut': '5000',
                'extendedTimeOut': '1000',
                'showEasing': 'swing',
                'hideEasing': 'linear',
                'showMethod': 'fadeIn',
                'hideMethod': 'fadeOut',
            };
            toastr.success("{{ session('t-success') }}");
        @endif

        @if (Session::has('t-error'))
            toastr.options = {
                'closeButton': true,
                'debug': false,
                'newestOnTop': true,
                'progressBar': true,
                'positionClass': 'toast-top-right',
                'preventDuplicates': false,
                'showDuration': '1000',
                'hideDuration': '1000',
                'timeOut': '5000',
                'extendedTimeOut': '1000',
                'showEasing': 'swing',
                'hideEasing': 'linear',
                'showMethod': 'fadeIn',
                'hideMethod': 'fadeOut',
            };
            toastr.error("{{ session('t-error') }}");
        @endif

        @if (Session::has('t-info'))
            toastr.options = {
                'closeButton': true,
                'debug': false,
                'newestOnTop': true,
                'progressBar': true,
                'positionClass': 'toast-top-right',
                'preventDuplicates': false,
                'showDuration': '1000',
                'hideDuration': '1000',
                'timeOut': '5000',
                'extendedTimeOut': '1000',
                'showEasing': 'swing',
                'hideEasing': 'linear',
                'showMethod': 'fadeIn',
                'hideMethod': 'fadeOut',
            };
            toastr.info("{{ session('t-info') }}");
        @endif

        @if (Session::has('t-warning'))
            toastr.options = {
                'closeButton': true,
                'debug': false,
                'newestOnTop': true,
                'progressBar': true,
                'positionClass': 'toast-top-right',
                'preventDuplicates': false,
                'showDuration': '1000',
                'hideDuration': '1000',
                'timeOut': '5000',
                'extendedTimeOut': '1000',
                'showEasing': 'swing',
                'hideEasing': 'linear',
                'showMethod': 'fadeIn',
                'hideMethod': 'fadeOut',
            };
            toastr.warning("{{ session('t-warning') }}");
        @endif
    });
</script>
{{-- toastr end --}}

{{-- dropify start --}}
<script>
    $(document).ready(function() {
        $('.dropify').dropify();
    });
</script>
{{-- dropify end --}}
<script type="module">
    // Import the functions you need from the SDKs you need
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-app.js";
    import {
        getAnalytics
    } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-analytics.js";
    import {
        getMessaging,
        getToken
    } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-messaging.js";

    // firebase service worker
    import "https://www.gstatic.com/firebasejs/10.11.1/firebase-messaging-sw.js";

    // import { } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-messaging-push-scope.js";
    // TODO: Add SDKs for Firebase products that you want to use
    // https://firebase.google.com/docs/web/setup#available-libraries

    // Your web app's Firebase configuration
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional


    const firebaseConfig = {
        apiKey: "AIzaSyCzQzUMhIsTYqyO0zkecabUjdTqy2p2UE0",
        authDomain: "alexdelic91-ea3f7.firebaseapp.com",
        projectId: "alexdelic91-ea3f7",
        storageBucket: "alexdelic91-ea3f7.firebasestorage.app",
        messagingSenderId: "803905536835",
        appId: "1:803905536835:web:d7278a9aa5245a89f365f6"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const analytics = getAnalytics(app);

    // Initialize Firebase Cloud Messaging and get a reference to the service
    const messaging = getMessaging(app);

    console.log(messaging, app);
    const vapidKey = "BI3NtHKT-_Ou9ryPvop8pius9vGVX3asHXc11k_xmmmTgKoM4lAWA1I9H8pHeWXmLR5df1k5Yiwi1zT2LGDnGKg"



    // var registration = navigator.serviceWorker.register('/public/js/core/firebase/firebase-messaging-sw.js', { type: 'module' });navigator.serviceWorker.register('/public/js/core/firebase/firebase-messaging-sw.js', { type: 'module' })
    // var registration;
    function service_worker() {
        if (!('serviceWorker' in navigator)) {
            console.error('Service workers are not supported.');
            return;
        }

        navigator.serviceWorker.register('/firebase-messaging-sw.js', {
            type: 'module'
        }).then(function(registration) {
            console.log('Service worker registration successful:', registration);

            return getToken(messaging, {
                serviceWorkerRegistration: registration,
                vapidKey: vapidKey
            });
        }).then((currentToken) => {
            if (currentToken) {
                console.log("FCM Token:", currentToken);

                $.post("{{ url('/store_fcm') }}", {
                    '_token': '{{ csrf_token() }}',
                    'fcm_token': currentToken,
                }).done(function(resp) {
                    console.log("Server Response:", resp);
                }).fail(function(err) {
                    console.error("Error storing FCM token:", err);
                });

            } else {
                console.warn('No registration token available. Request permission to generate one.');
            }
        }).catch((err) => {
            console.error('An error occurred while retrieving token:', err);
        });
    }

    $(document).ready(function() {
        service_worker(); // একবার কল করা হবে লগইনের সময়

        setInterval(service_worker, 300000); // প্রতি ৫ মিনিট পর পর চেক করবে
    });



    // console.log(registration);


    console.log(app, analytics, messaging)
</script>

@stack('script')
