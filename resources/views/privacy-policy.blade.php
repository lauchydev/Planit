<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Privacy Policy - {{ config('app.name', 'Planit') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold text-gray-900">Planit</h1>
                </div>
            </header>

            <!-- Main Content -->
            <div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm rounded-lg p-8 lg:p-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Privacy Policy & Terms of Use</h1>
            <p class="text-sm text-gray-600 mb-8">Last Updated: {{ date('F d, Y') }}</p>

            <!-- Introduction -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Introduction</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Welcome to Planit, an event management and booking platform. We are committed to protecting your privacy 
                    and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, 
                    store, and protect your data when you use our services.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    By creating an account and using Planit, you explicitly consent to the collection and use of your 
                    information as described in this policy.
                </p>
            </section>

            <!-- What Data We Collect -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. What Data We Collect</h2>
                <p class="text-gray-700 leading-relaxed mb-3">
                    We collect the following personal information from you:
                </p>
                <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                    <li><strong>Account Information:</strong> Name, email address, and password (encrypted)</li>
                    <li><strong>User Type:</strong> Whether you are registered as an Attendee or Organiser</li>
                    <li><strong>Booking History:</strong> Records of events you have booked or attended</li>
                    <li><strong>Event Information:</strong> If you are an Organiser, details of events you create (title, description, date, time, location, capacity)</li>
                    <li><strong>Usage Data:</strong> Information about how you interact with our platform (page visits, search queries, filters applied)</li>
                </ul>
            </section>

            <!-- Why We Collect This Data -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Why We Collect This Data</h2>
                <p class="text-gray-700 leading-relaxed mb-3">
                    We collect and process your personal data for the following purposes:
                </p>
                <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                    <li><strong>Authentication:</strong> To verify your identity and provide secure access to your account</li>
                    <li><strong>Event Participation:</strong> To enable you to book events, manage your bookings, and receive event-related information</li>
                    <li><strong>Event Management:</strong> To allow Organisers to create, manage, and track attendance for their events</li>
                    <li><strong>Communication:</strong> To send you notifications about your bookings and account (if future features are added)</li>
                    <li><strong>Service Improvement:</strong> To understand how users interact with our platform and improve functionality</li>
                    <li><strong>Legal Compliance:</strong> To comply with applicable laws and regulations</li>
                </ul>
            </section>

            <!-- How We Store and Protect Your Data -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. How We Store and Protect Your Data</h2>
                <div class="space-y-4 text-gray-700">
                    <p class="leading-relaxed">
                        <strong>Security Measures:</strong> We implement industry-standard security measures to protect your data:
                    </p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li><strong>Password Encryption:</strong> All passwords are hashed using bcrypt encryption and never stored in plain text</li>
                        <li><strong>Secure Database:</strong> Your data is stored in a secure SQLite database with restricted access</li>
                        <li><strong>CSRF Protection:</strong> All forms are protected against Cross-Site Request Forgery attacks</li>
                        <li><strong>Authentication:</strong> Session-based authentication ensures only authorized users can access protected resources</li>
                        <li><strong>Authorization:</strong> Role-based access control ensures users can only access and modify data they're permitted to</li>
                    </ul>
                    <p class="leading-relaxed mt-4">
                        <strong>Data Retention:</strong> We retain your personal data for as long as your account remains active. 
                        Upon account deletion, your personal information will be permanently removed from our systems, except where 
                        retention is required by law.
                    </p>
                </div>
            </section>

            <!-- Your Rights -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Your Rights</h2>
                <p class="text-gray-700 leading-relaxed mb-3">
                    You have the following rights regarding your personal data:
                </p>
                <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                    <li><strong>Access:</strong> You can view and access your personal information at any time through your profile page</li>
                    <li><strong>Correction:</strong> You can update and correct your personal information (name, email) through your account settings</li>
                    <li><strong>Deletion:</strong> You can request deletion of your account and associated data by contacting us or using the account deletion feature</li>
                    <li><strong>Data Portability:</strong> You can request a copy of your personal data in a machine-readable format</li>
                    <li><strong>Withdrawal of Consent:</strong> You can withdraw your consent at any time by deleting your account</li>
                </ul>
            </section>

            <!-- Data Sharing -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Data Sharing and Disclosure</h2>
                <p class="text-gray-700 leading-relaxed mb-3">
                    We respect your privacy and do not sell, rent, or trade your personal information. Limited data may be visible to other users:
                </p>
                <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                    <li><strong>Event Organisers:</strong> Can see the names of attendees who have booked their events</li>
                    <li><strong>Public Event Listings:</strong> Event details (not attendee information) are publicly visible</li>
                    <li><strong>Organiser Names:</strong> Displayed on events they create for transparency</li>
                </ul>
                <p class="text-gray-700 leading-relaxed mt-4">
                    We will only disclose your information to third parties if required by law, court order, or to protect 
                    our rights and safety.
                </p>
            </section>

            <!-- Cookies and Tracking -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Cookies and Tracking</h2>
                <p class="text-gray-700 leading-relaxed">
                    We use essential cookies to maintain your login session and remember your preferences. These cookies are 
                    necessary for the platform to function properly. We do not use third-party advertising or tracking cookies.
                </p>
            </section>

            <!-- Terms of Use -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Terms of Use</h2>
                <div class="space-y-4 text-gray-700">
                    <p class="leading-relaxed">
                        By using Planit, you agree to:
                    </p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Provide accurate and truthful information during registration</li>
                        <li>Maintain the security and confidentiality of your account credentials</li>
                        <li>Not use the platform for any unlawful or fraudulent purposes</li>
                        <li>Not attempt to gain unauthorized access to other users' accounts or data</li>
                        <li>Respect the intellectual property rights of the platform and other users</li>
                    </ul>
                    <p class="leading-relaxed mt-4">
                        <strong>Organiser Responsibilities:</strong> Event organisers are responsible for the accuracy of event 
                        information and must comply with all applicable laws regarding their events.
                    </p>
                    <p class="leading-relaxed">
                        <strong>Attendee Responsibilities:</strong> Attendees must honor their bookings and notify organisers 
                        of cancellations in a timely manner.
                    </p>
                </div>
            </section>

            <!-- Changes to This Policy -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Changes to This Policy</h2>
                <p class="text-gray-700 leading-relaxed">
                    We may update this Privacy Policy from time to time to reflect changes in our practices or for legal, 
                    operational, or regulatory reasons. We will notify users of significant changes by updating the 
                    "Last Updated" date at the top of this page. Continued use of the platform after changes constitutes 
                    acceptance of the updated policy.
                </p>
            </section>

            <!-- Contact Information -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. Contact Us</h2>
                <p class="text-gray-700 leading-relaxed mb-3">
                    If you have any questions, concerns, or requests regarding this Privacy Policy or your personal data, 
                    please contact us at:
                </p>
                <div class="bg-gray-50 p-4 rounded-md">
                    <p class="text-gray-700"><strong>Email:</strong> privacy@planit-events.com</p>
                    <p class="text-gray-700"><strong>Support:</strong> support@planit-events.com</p>
                </div>
            </section>

            <!-- Consent -->
            <section class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">Your Consent</h3>
                <p class="text-blue-800 leading-relaxed">
                    By checking the consent box during registration and creating an account, you acknowledge that you have 
                    read, understood, and agree to this Privacy Policy and Terms of Use. You consent to the collection, 
                    use, and processing of your personal data as described herein.
                </p>
            </section>

            <!-- Back to Registration -->
            <div class="mt-8 text-center">
                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Registration
                </a>
            </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 mt-12">
                <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="text-center text-sm text-gray-600">
                        <p>© {{ date('Y') }} Planit - Event Management Platform</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
