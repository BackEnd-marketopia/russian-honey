<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
    import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging.js";

    const firebaseConfig = {
        apiKey: "AIzaSyB-WxMi7PUslnL09H6DZczK6mUSbUzwMcM",
        authDomain: "russian-honey-42c45.firebaseapp.com",
        projectId: "russian-honey-42c45",
        storageBucket: "russian-honey-42c45.firebasestorage.app",
        messagingSenderId: "414953360568",
        appId: "1:414953360568:web:dfb38ae6e5b0b2334bf84d",
        vapidKey: "BD6I5-oGpzYDAX_kLdRoYCUxCUP2c6NchK-UAW2A6RnpVSEKigsq2lI2LT9QiTmq74guLj6sUkivjkmM_MDs2_o",
    };

    const app = initializeApp(firebaseConfig);
    const messaging = getMessaging(app);

    // جلب FCM Token
    getToken(messaging, { vapidKey: firebaseConfig.vapidKey }).then((token) => {
        if (token) {
            console.log("FCM Token:", token);
            fetch("{{ route('admin.save-fcm-token') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ 
                    fcm_token: token,
                    user_id: {{ auth('web')->user()?->id }} 
                 })
            });
        }
    });    // استقبال Notification
    onMessage(messaging, (payload) => {
        console.log("New order notification", payload);
        Swal.fire({
            title:  '{{ __("message.New Order") }}',
            text:  `{{ __("message.You have a new order") }}`,
            icon: 'success',
            confirmButtonText: '{{ __("message.View Orders") }}',
            confirmButtonColor: '#F4B700',
            showCancelButton: true,
            cancelButtonText: '{{ __("message.Close") }}',
            cancelButtonColor: '#FAFAFA',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("filament.admin.resources.orders.index") }}';
            }
        });
    });
</script>
    