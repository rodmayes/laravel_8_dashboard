@extends('layouts.app')
@section('content')
    <div class="bg-gray-100">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-4">Privacy Policy</h1>

            <p class="mb-4">
                This privacy policy sets out how our website uses and protects any information that you give us when you use this website [{{ env('APP_URL') }}].
            </p>

            <h2 class="text-2xl font-bold mb-2">Information We Collect</h2>

            <p class="mb-4">
                We may collect the following information:
            </p>

            <ul class="list-disc list-inside mb-4">
                <li>Your name and email information</li>
            </ul>

            <h2 class="text-2xl font-bold mb-2">How We Use the Information</h2>

            <p class="mb-4">
                We require this information to understand your needs and provide you with a better service, and in particular for the following reasons:
            </p>

            <ul class="list-disc list-inside mb-4">
                <li>Internal record keeping</li>
                <li>Improving our products and services</li>
                <li>From time to time, we may also use your information to contact you for market research purposes. We may contact you by email, phone, or mail. We may use the information to customize the website according to your interests.</li>
            </ul>

            <h2 class="text-2xl font-bold mb-2">Security</h2>

            <p class="mb-4">
                We are committed to ensuring that your information is secure. In order to prevent unauthorized access or disclosure, we have put in place suitable physical, electronic, and managerial procedures to safeguard and secure the information we collect online.
            </p>

            <h2 class="text-2xl font-bold mb-2">Cookies</h2>

            <p class="mb-4">
                A cookie is a small file that asks permission to be placed on your computer's hard drive. Once you agree, the file is added, and the cookie helps analyze web traffic or lets you know when you visit a particular site.
                Cookies allow web applications to respond to you as an individual. The web application can tailor its operations to your needs, likes, and dislikes by gathering and remembering information about your preferences.
            </p>

            <p class="mb-4">
                Overall, cookies help us provide you with a better website by enabling us to monitor which pages you find useful and which you do not. A cookie in no way gives us access to your computer or any information about you, other than the data you choose to share with us.
            </p>

            <h2 class="text-2xl font-bold mb-2">Controlling Your Personal Information</h2>

            <p class="mb-4">
                You may choose to restrict the collection or use of your personal information in the following ways:
            </p>

            <ul class="list-disc list-inside mb-4">
                <li>If you have previously agreed to us using your personal information for direct marketing purposes, you may change your mind at any time by writing to or emailing us at [email protected]</li>
                <li>You may request details of personal information which we hold about you. If you would like a copy of the information held on you, please write to email
                    {{ env('EMAIL_POLICY_AND_LEGAL', 'rodmayes@gmail.com') }}</li>
                <li>If you believe that any information we are holding on you is incorrect or incomplete, please write to or email us as soon as possible at the above address. We will promptly correct any information found to be incorrect.
                </li>
            </ul>

            <p class="mb-4">
                This privacy policy is subject to change without notice.
            </p>
        </div>
    </div>

@endsection
