<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\GradeGuideline;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'askuniva@gmail.com',
            'password' => Hash::make('password'),
            'grade' => 'grade_12',
            'role' => 'admin',
        ]);

        // Create Default Site Settings
        $settings = [
            // Hero Section
            ['key' => 'hero_title', 'value' => 'Welcome to Univa', 'type' => 'text', 'group' => 'hero'],
            ['key' => 'hero_subtext', 'value' => 'Your AI-powered virtual college counselor. Get personalized guidance for your university preparation journey, from Grade 9 through Grade 12.', 'type' => 'textarea', 'group' => 'hero'],

            // Footer
            ['key' => 'footer_links', 'value' => json_encode([
                ['label' => 'Home', 'url' => '/'],
                ['label' => 'About', 'url' => '/about'],
                ['label' => 'Privacy', 'url' => '/privacy'],
                ['label' => 'Terms', 'url' => '/terms'],
                ['label' => 'Contact', 'url' => '/contact'],
            ]), 'type' => 'json', 'group' => 'footer'],
            ['key' => 'copyright_text', 'value' => 'Univa. All rights reserved.', 'type' => 'text', 'group' => 'footer'],

            // Chatfuel (placeholder)
            ['key' => 'chatfuel_bot_id', 'value' => '', 'type' => 'text', 'group' => 'integrations'],
            ['key' => 'chatfuel_token', 'value' => '', 'type' => 'text', 'group' => 'integrations'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::create($setting);
        }

        // Create Default Pages
        $pages = [
            [
                'slug' => 'about',
                'title' => 'About Univa',
                'content' => '<p>Univa is an AI-powered virtual college counselor designed to help high school students prepare for university.</p>
<h2>Our Mission</h2>
<p>We believe every student deserves access to quality college counseling. Our mission is to democratize college preparation by providing personalized, AI-powered guidance to students regardless of their background or location.</p>
<h2>What We Offer</h2>
<ul>
<li><strong>Grade-Specific Guidance:</strong> Tailored advice based on your current grade level</li>
<li><strong>AI College Advisor:</strong> 24/7 access to our intelligent counseling chatbot</li>
<li><strong>Comprehensive Resources:</strong> Everything you need to know about college preparation</li>
</ul>
<h2>Contact Us</h2>
<p>Have questions? Visit our <a href="/contact">Contact page</a> to get in touch.</p>',
            ],
            [
                'slug' => 'privacy',
                'title' => 'Privacy Policy',
                'content' => '<p>Last updated: ' . date('F j, Y') . '</p>
<h2>Information We Collect</h2>
<p>We collect information you provide directly to us, such as when you create an account, use our services, or contact us.</p>
<h2>How We Use Your Information</h2>
<p>We use the information we collect to provide, maintain, and improve our services, and to communicate with you.</p>
<h2>Information Sharing</h2>
<p>We do not share your personal information with third parties except as described in this policy or with your consent.</p>
<h2>Data Security</h2>
<p>We take reasonable measures to help protect your personal information from loss, theft, misuse, and unauthorized access.</p>
<h2>Contact Us</h2>
<p>If you have questions about this Privacy Policy, please contact us through our <a href="/contact">Contact page</a>.</p>',
            ],
            [
                'slug' => 'terms',
                'title' => 'Terms of Service',
                'content' => '<p>Last updated: ' . date('F j, Y') . '</p>
<h2>Acceptance of Terms</h2>
<p>By accessing and using Univa, you agree to be bound by these Terms of Service.</p>
<h2>Description of Service</h2>
<p>Univa provides AI-powered college counseling services to help students prepare for university applications.</p>
<h2>User Accounts</h2>
<p>You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.</p>
<h2>Acceptable Use</h2>
<p>You agree not to use the service for any unlawful purpose or in any way that could damage, disable, or impair our services.</p>
<h2>Disclaimer</h2>
<p>The advice provided by Univa is for informational purposes only and should not be considered as professional counseling advice.</p>
<h2>Changes to Terms</h2>
<p>We reserve the right to modify these terms at any time. Continued use of the service constitutes acceptance of modified terms.</p>',
            ],
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }

        // Create Grade Guidelines
        $guidelines = [
            [
                'grade' => 'grade_9_10',
                'title' => 'College Preparation for Grades 9 & 10',
                'content' => '<h2>Building Your Foundation</h2>
<p>Grades 9 and 10 are crucial for building the academic and extracurricular foundation that will support your college applications later.</p>

<h3>Academic Focus</h3>
<ul>
<li><strong>Take challenging courses:</strong> Enroll in honors or advanced classes when appropriate</li>
<li><strong>Build strong study habits:</strong> Develop effective time management and study techniques</li>
<li><strong>Focus on core subjects:</strong> Excel in English, Math, Science, Social Studies, and Foreign Language</li>
<li><strong>Maintain a strong GPA:</strong> Your freshman and sophomore grades count toward your cumulative GPA</li>
</ul>

<h3>Extracurricular Activities</h3>
<ul>
<li><strong>Explore your interests:</strong> Try various clubs, sports, and activities</li>
<li><strong>Find your passion:</strong> Begin focusing on 2-3 activities you truly enjoy</li>
<li><strong>Seek leadership:</strong> Start taking on responsibilities within organizations</li>
<li><strong>Volunteer:</strong> Begin community service and develop a commitment to giving back</li>
</ul>

<h3>College Exploration</h3>
<ul>
<li>Start researching colleges and what they look for</li>
<li>Attend college fairs and information sessions</li>
<li>Talk to older students about their college experiences</li>
<li>Consider what size, location, and type of school might interest you</li>
</ul>',
            ],
            [
                'grade' => 'grade_11',
                'title' => 'College Preparation for Grade 11',
                'content' => '<h2>The Critical Year</h2>
<p>Junior year is often considered the most important year for college admissions. Colleges pay close attention to your academic performance and activities during this time.</p>

<h3>Academic Priorities</h3>
<ul>
<li><strong>Take the most rigorous schedule you can handle:</strong> AP, IB, or honors courses</li>
<li><strong>Maintain excellent grades:</strong> Junior year grades are heavily weighted in admissions</li>
<li><strong>Prepare for standardized tests:</strong> Take the PSAT, plan for SAT/ACT</li>
<li><strong>Consider SAT Subject Tests:</strong> If required by your target schools</li>
</ul>

<h3>Standardized Testing</h3>
<ul>
<li><strong>PSAT (October):</strong> Take the PSAT for National Merit Scholarship consideration</li>
<li><strong>SAT/ACT (Spring):</strong> Take your first official SAT or ACT</li>
<li><strong>AP Exams (May):</strong> Prepare thoroughly for AP exams</li>
<li><strong>Consider test prep:</strong> Utilize study resources or prep courses</li>
</ul>

<h3>College Research</h3>
<ul>
<li><strong>Create a college list:</strong> Research 15-20 schools of varying selectivity</li>
<li><strong>Visit colleges:</strong> Plan campus visits during spring break or summer</li>
<li><strong>Attend information sessions:</strong> Meet with college representatives</li>
<li><strong>Identify potential majors:</strong> Explore academic interests</li>
</ul>

<h3>Extracurriculars & Leadership</h3>
<ul>
<li>Deepen involvement in your key activities</li>
<li>Take on significant leadership roles</li>
<li>Start meaningful projects or initiatives</li>
<li>Continue community service</li>
</ul>',
            ],
            [
                'grade' => 'grade_12',
                'title' => 'College Preparation for Grade 12',
                'content' => '<h2>The Application Year</h2>
<p>Senior year is when all your preparation comes together. Stay focused on academics while completing your applications.</p>

<h3>Fall Semester - Application Season</h3>
<ul>
<li><strong>Finalize your college list:</strong> Aim for 8-12 schools (reach, match, safety)</li>
<li><strong>Complete applications:</strong> Start with Early Decision/Action deadlines</li>
<li><strong>Write compelling essays:</strong> Begin drafting essays over the summer</li>
<li><strong>Request recommendations:</strong> Ask teachers and counselors early</li>
<li><strong>Submit FAFSA/CSS Profile:</strong> Apply for financial aid starting October 1</li>
</ul>

<h3>Key Deadlines</h3>
<ul>
<li><strong>October-November:</strong> Early Decision/Early Action deadlines</li>
<li><strong>January 1-15:</strong> Regular Decision deadlines for most schools</li>
<li><strong>February:</strong> Complete remaining applications and scholarship apps</li>
<li><strong>March-April:</strong> Receive decisions, compare financial aid offers</li>
<li><strong>May 1:</strong> National College Decision Day</li>
</ul>

<h3>Application Components</h3>
<ul>
<li><strong>Common App/Coalition App:</strong> Complete the main application platform</li>
<li><strong>Personal Essay:</strong> Write a compelling, authentic personal statement</li>
<li><strong>Supplemental Essays:</strong> Research each school and write thoughtful responses</li>
<li><strong>Activity List:</strong> Present your extracurriculars effectively</li>
<li><strong>Test Scores:</strong> Send official SAT/ACT scores</li>
<li><strong>Transcripts:</strong> Request official transcripts from your school</li>
</ul>

<h3>Maintain Your Momentum</h3>
<ul>
<li>Keep your grades strong - colleges review senior year performance</li>
<li>Continue your extracurricular involvement</li>
<li>Avoid "senioritis" - it can lead to rescinded admissions</li>
<li>Update colleges on significant achievements</li>
</ul>',
            ],
        ];

        foreach ($guidelines as $guideline) {
            GradeGuideline::create($guideline);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin login: askuniva@gmail.com / password');
    }
}
