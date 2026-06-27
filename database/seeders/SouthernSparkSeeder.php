<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Day;
use App\Models\Session;
use App\Models\Speaker;
use App\Models\Track;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SouthernSparkSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('saved_sessions')->truncate();
        DB::table('session_speaker')->truncate();
        DB::table('session_feedback')->truncate();
        DB::table('sessions')->truncate();
        DB::table('speakers')->truncate();
        DB::table('tracks')->truncate();
        DB::table('announcements')->truncate();
        DB::table('days')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $days = [
            'day1' => Day::create([
                'name' => 'Day 1',
                'event_date' => '2026-06-11',
                'description' => 'AI practice, systems, and applied classroom tools across three rooms.',
                'sort_order' => 1,
            ]),
            'day2' => Day::create([
                'name' => 'Day 2',
                'event_date' => '2026-06-12',
                'description' => 'Networking, implementation, policy, and ecosystem sessions across five rooms.',
                'sort_order' => 2,
            ]),
        ];

        $tracks = [
            'ballroom_a' => $this->createTrack('Ballroom A', 'Primary room for practitioner, educator, and plenary programming.'),
            'ballroom_b' => $this->createTrack('Ballroom B', 'Primary room for development, systems, and regional innovation programming.'),
            'breakout_1' => $this->createTrack('Breakout Room 1', 'Breakout room for applied classroom and showcase sessions.'),
            'breakout_2' => $this->createTrack('Breakout Room 2', 'Breakout room for institutional, legal, and social impact sessions.'),
            'breakout_3' => $this->createTrack('Breakout Room 3', 'Breakout room for ecosystem and special-topic conversations.'),
        ];

        $speakers = [];

        $sessionData = [
            [
                'day' => 'day1',
                'track' => 'ballroom_a',
                'title' => 'Registration & Coffee',
                'speaker' => null,
                'session_type' => 'Networking',
                'start_time' => '09:00:00',
                'end_time' => '09:30:00',
                'location' => 'Ballroom A',
                'description' => 'Check in, grab coffee, and connect with fellow attendees before the conference begins.',
            ],
            [
                'day' => 'day1',
                'track' => 'ballroom_a',
                'title' => 'Welcome & Orientation',
                'speaker' => null,
                'session_type' => 'Welcome',
                'start_time' => '09:30:00',
                'end_time' => '09:40:00',
                'location' => 'Ballroom A',
                'description' => 'Opening welcome and orientation for Day 1 attendees.',
            ],

            [
                'day' => 'day1',
                'track' => 'ballroom_a',
                'title' => 'Mastering Personalized AI with Google NotebookLM',
                'speaker' => 'Reginald Matthews',
                'title_line' => 'Director of Information Technology',
                'organization' => 'Southwest Mississippi Community College',
                'session_type' => 'Workshop',
                'start_time' => '09:40:00',
                'end_time' => '10:55:00',
                'location' => 'Ballroom A',
                'description' => 'A hands-on workshop exploring personalized AI workflows with Google NotebookLM.',
                'is_featured' => true,
            ],
            [
                'day' => 'day1',
                'track' => 'ballroom_b',
                'title' => 'Out of the Building-Now: Using AI to Accelerate Real-World Customer Discovery',
                'speaker' => 'Latane Brackett',
                'title_line' => 'Innovation Program Manager',
                'organization' => 'Jackson State University',
                'session_type' => 'Workshop',
                'start_time' => '09:40:00',
                'end_time' => '10:55:00',
                'location' => 'Ballroom B',
                'description' => 'A practical session on using AI to move customer discovery work from concept to action faster.',
                'is_featured' => true,
            ],
            [
                'day' => 'day1',
                'track' => 'breakout_1',
                'title' => 'Socratic by Design, Safe by Default: Khan Academy + Khanmigo in Action',
                'speaker' => 'Heather White and Nick Joe',
                'title_line' => 'Instructional Technology Facilitator and District Success Manager',
                'organization' => 'Livingston Parish Schools and Khan Academy',
                'session_type' => 'Workshop',
                'start_time' => '09:40:00',
                'end_time' => '10:55:00',
                'location' => 'Breakout Room 1',
                'description' => 'A practical classroom session on using Khan Academy and Khanmigo with safety and intentional instructional design.',
                'is_featured' => true,
            ],

            [
                'day' => 'day1',
                'track' => 'ballroom_a',
                'title' => 'Break + Networking',
                'speaker' => null,
                'session_type' => 'Break',
                'start_time' => '10:55:00',
                'end_time' => '11:15:00',
                'location' => 'Ballroom A',
                'description' => 'Coffee refill, hallway conversations, and networking between session blocks.',
            ],

            [
                'day' => 'day1',
                'track' => 'ballroom_a',
                'title' => 'From Learning to Earning: The A.I.M. Framework in Action',
                'speaker' => 'Yolanda Edmonds',
                'title_line' => 'Founder and Owner',
                'organization' => 'Lane Business Solutions',
                'session_type' => 'Workshop',
                'start_time' => '11:15:00',
                'end_time' => '12:30:00',
                'location' => 'Ballroom A',
                'description' => 'A practical framework for moving from AI learning to tangible value and implementation.',
                'is_featured' => true,
            ],
            [
                'day' => 'day1',
                'track' => 'ballroom_b',
                'title' => 'From Idea to Reality: Building Smarter Workflows with Claude Code and Claude Cowork',
                'speaker' => 'William Hill',
                'title_line' => 'Senior Software Engineer',
                'organization' => 'Zocdoc',
                'session_type' => 'Workshop',
                'start_time' => '11:15:00',
                'end_time' => '12:30:00',
                'location' => 'Ballroom B',
                'description' => 'A systems-focused build session on creating smarter workflows with Claude-based tooling.',
                'is_featured' => true,
            ],
            [
                'day' => 'day1',
                'track' => 'breakout_1',
                'title' => 'Teaching With AI, Not Around It: Purpose-Driven Classroom AI',
                'speaker' => 'Shauna Waters',
                'title_line' => 'ELA Teacher, Tech Champion, and Department Chair',
                'organization' => 'West Lauderdale High School',
                'session_type' => 'Workshop',
                'start_time' => '11:15:00',
                'end_time' => '12:30:00',
                'location' => 'Breakout Room 1',
                'description' => 'A classroom-centered workshop on using AI intentionally and purposefully in teaching practice.',
                'is_featured' => true,
            ],

            [
                'day' => 'day1',
                'track' => 'ballroom_a',
                'title' => 'Lunch Keynote - Dr. Chris Chism',
                'speaker' => 'Dr. Chris Chism',
                'title_line' => 'Keynote Speaker',
                'organization' => 'Southern Spark',
                'session_type' => 'Keynote',
                'start_time' => '12:30:00',
                'end_time' => '13:30:00',
                'location' => 'Ballroom A',
                'description' => 'Lunch keynote address from Dr. Chris Chism.',
                'is_featured' => true,
            ],

            [
                'day' => 'day1',
                'track' => 'ballroom_a',
                'title' => 'AI Is Here. Now What Do We Teach? Building AI Literacy With Secondary Students in the South',
                'speaker' => 'Candace McClendon',
                'title_line' => 'Instructional Coach',
                'organization' => 'Harrison County School District',
                'session_type' => 'Talk',
                'start_time' => '13:30:00',
                'end_time' => '14:45:00',
                'location' => 'Ballroom A',
                'description' => 'A classroom-centered session on building AI literacy with secondary students in the South.',
            ],
            [
                'day' => 'day1',
                'track' => 'ballroom_b',
                'title' => 'Building with LLMs',
                'speaker' => 'Brandon Newton, David Placeholder, and Bob Placeholder',
                'title_line' => 'Technical Speaker Panel',
                'organization' => 'Southern Spark',
                'session_type' => 'Panel',
                'start_time' => '13:30:00',
                'end_time' => '14:45:00',
                'location' => 'Ballroom B',
                'description' => 'A practical session on building with LLMs. Placeholder names are being used until final speaker details are confirmed.',
            ],
            [
                'day' => 'day1',
                'track' => 'breakout_1',
                'title' => "Mississippi's K-12 AI Policy and Teacher Training",
                'speaker' => 'Melissa Placeholder and John Placeholder',
                'title_line' => 'Education Policy Speakers',
                'organization' => 'Mississippi Department of Education',
                'session_type' => 'Talk',
                'start_time' => '13:30:00',
                'end_time' => '14:45:00',
                'location' => 'Breakout Room 1',
                'description' => "A Mississippi Department of Education session on the state's K-12 AI policy and teacher training efforts.",
            ],

            [
                'day' => 'day1',
                'track' => 'ballroom_a',
                'title' => 'Break + Networking',
                'speaker' => null,
                'session_type' => 'Break',
                'start_time' => '14:45:00',
                'end_time' => '15:05:00',
                'location' => 'Ballroom A',
                'description' => 'Networking break between the afternoon session blocks.',
            ],

            [
                'day' => 'day1',
                'track' => 'ballroom_a',
                'title' => 'From Uh-Uh to AI: Incorporating AI in the Classroom',
                'speaker' => 'Carlos Ewing',
                'title_line' => 'Founder and AI Educator',
                'organization' => 'ESAI AI',
                'session_type' => 'Talk',
                'start_time' => '15:05:00',
                'end_time' => '16:10:00',
                'location' => 'Ballroom A',
                'description' => 'A classroom implementation session focused on practical AI incorporation for educators.',
            ],
            [
                'day' => 'day1',
                'track' => 'ballroom_b',
                'title' => 'Emerging/AI Technology Strategy Gaming for Social Impact',
                'speaker' => 'Luciano Oviedo and Dell Gines',
                'title_line' => 'Innovation Leaders',
                'organization' => 'IEDC (International Economic Development Council)',
                'session_type' => 'Talk',
                'start_time' => '15:05:00',
                'end_time' => '16:10:00',
                'location' => 'Ballroom B',
                'description' => 'A strategy-forward session on gaming emerging AI technology decisions for social impact outcomes.',
            ],
            [
                'day' => 'day1',
                'track' => 'breakout_1',
                'title' => 'Teaching With AI, Not Around It: Purpose-Driven Classroom AI',
                'speaker' => 'Shauna Waters',
                'title_line' => 'ELA Teacher, Tech Champion, and Department Chair',
                'organization' => 'West Lauderdale High School',
                'session_type' => 'Clinic',
                'start_time' => '15:05:00',
                'end_time' => '16:10:00',
                'location' => 'Breakout Room 1',
                'description' => 'An advanced classroom clinic version of purpose-driven AI teaching practice. Final format may also repeat the Khanmigo workshop if needed.',
            ],

            [
                'day' => 'day1',
                'track' => 'ballroom_a',
                'title' => 'AI Solution Design Lab (All Attendees)',
                'speaker' => null,
                'session_type' => 'Interactive Lab',
                'start_time' => '16:10:00',
                'end_time' => '17:00:00',
                'location' => 'Ballroom A',
                'description' => 'Interactive closing activity where teams form around a regional challenge, design an AI solution, and deliver rapid presentations. Includes challenge briefing, brainstorming, and presentations.',
                'is_featured' => true,
            ],
            [
                'day' => 'day1',
                'track' => 'ballroom_a',
                'title' => 'Networking Reception - The Bean Path Makerspace',
                'speaker' => null,
                'session_type' => 'Networking Reception',
                'start_time' => '17:00:00',
                'end_time' => '19:00:00',
                'location' => 'The Bean Path Makerspace',
                'description' => 'Evening networking reception for attendees, speakers, sponsors, and ecosystem builders.',
                'is_featured' => true,
            ],
            [
                'day' => 'day2',
                'track' => 'ballroom_a',
                'title' => 'Coffee, Networking, and Opening Remarks',
                'speaker' => null,
                'session_type' => 'Welcome',
                'start_time' => '09:00:00',
                'end_time' => '09:30:00',
                'location' => 'Ballroom A',
                'description' => 'Coffee, networking, and opening remarks to start Day 2.',
            ],
            [
                'day' => 'day2',
                'track' => 'ballroom_a',
                'title' => 'Plenary Opening Panel: AI Policy & Data Governance Panel: Statewide Findings',
                'speaker' => 'James Robinson, Eva Harvell, Lamarus Norman, and Gigi Mims',
                'title_line' => 'Panelists',
                'organization' => 'Southern Spark',
                'session_type' => 'Plenary',
                'start_time' => '09:30:00',
                'end_time' => '10:00:00',
                'location' => 'Ballroom A',
                'description' => 'Plenary opening panel sharing statewide findings on AI policy and data governance.',
                'is_featured' => true,
            ],
            [
                'day' => 'day2',
                'track' => 'ballroom_a',
                'title' => 'Break / Transition',
                'speaker' => null,
                'session_type' => 'Break',
                'start_time' => '10:00:00',
                'end_time' => '10:10:00',
                'location' => 'Ballroom A',
                'description' => 'Short transition break between the plenary and breakout sessions.',
            ],

            [
                'day' => 'day2',
                'track' => 'ballroom_a',
                'title' => 'Open Networking',
                'speaker' => null,
                'session_type' => 'Networking',
                'start_time' => '10:10:00',
                'end_time' => '10:55:00',
                'location' => 'Ballroom A',
                'description' => 'Open networking time for attendee conversations, sponsor stops, and hallway meetings.',
            ],
            [
                'day' => 'day2',
                'track' => 'ballroom_b',
                'title' => 'Building a Statewide AI Innovation Hub for Public Impact',
                'speaker' => 'Shelley Thompson and Benjamin Graham',
                'title_line' => 'Emerging Technology Leaders',
                'organization' => 'Mississippi Department of Information Technology Services',
                'session_type' => 'Talk',
                'start_time' => '10:10:00',
                'end_time' => '10:55:00',
                'location' => 'Ballroom B',
                'description' => 'A session on building statewide innovation infrastructure for public impact.',
            ],
            [
                'day' => 'day2',
                'track' => 'breakout_1',
                'title' => 'Building an Ethical AI Learning Ecosystem: The FRAMER Model',
                'speaker' => 'Dr. Lorilyn Thompson',
                'title_line' => 'Associate Professor of Education',
                'organization' => 'Blue Mountain Christian University',
                'session_type' => 'Talk',
                'start_time' => '10:10:00',
                'end_time' => '10:55:00',
                'location' => 'Breakout 1',
                'description' => 'An ethics-centered framework for building an AI learning ecosystem.',
            ],
            [
                'day' => 'day2',
                'track' => 'breakout_2',
                'title' => 'Beyond the Tool: Building Institutional AI Capacity',
                'speaker' => 'Delaney Foster and Julie Jordan',
                'title_line' => 'AI and Innovation Leaders',
                'organization' => 'Mississippi State University',
                'session_type' => 'Talk',
                'start_time' => '10:10:00',
                'end_time' => '10:55:00',
                'location' => 'Breakout Room 2',
                'description' => 'A session focused on building institutional AI capacity beyond tool adoption.',
            ],
            [
                'day' => 'day2',
                'track' => 'breakout_3',
                'title' => 'MagnoliaJS - Building Tech and Development Ecosystem',
                'speaker' => 'MagnoliaJS Placeholder Speaker',
                'title_line' => 'Community Speaker',
                'organization' => 'MagnoliaJS',
                'session_type' => 'Talk',
                'start_time' => '10:10:00',
                'end_time' => '10:55:00',
                'location' => 'Breakout Room 3',
                'description' => 'A community and ecosystem conversation focused on building a stronger development scene.',
            ],

            [
                'day' => 'day2',
                'track' => 'ballroom_a',
                'title' => 'Open Networking',
                'speaker' => null,
                'session_type' => 'Networking',
                'start_time' => '11:00:00',
                'end_time' => '11:45:00',
                'location' => 'Ballroom A',
                'description' => 'Open networking time for attendee conversations, sponsor stops, and hallway meetings.',
            ],
            [
                'day' => 'day2',
                'track' => 'ballroom_b',
                'title' => 'The Landscape of AI in Education and Beyond',
                'speaker' => 'Tara Poolson',
                'title_line' => 'Technology Integration Specialist',
                'organization' => 'Pearl River County School District',
                'session_type' => 'Talk',
                'start_time' => '11:00:00',
                'end_time' => '11:45:00',
                'location' => 'Ballroom B',
                'description' => 'An educator-focused view of today\'s AI landscape and practical adoption considerations.',
            ],
            [
                'day' => 'day2',
                'track' => 'breakout_1',
                'title' => 'Closing the Rural Digital Divide with Google Colab',
                'speaker' => 'Tasha Penwell',
                'title_line' => 'Doctoral Candidate and Educator',
                'organization' => 'Ohio University',
                'session_type' => 'Talk',
                'start_time' => '11:00:00',
                'end_time' => '11:45:00',
                'location' => 'Breakout Room 1',
                'description' => 'A practical session on using Google Colab to expand access and close rural digital divides.',
            ],
            [
                'day' => 'day2',
                'track' => 'breakout_2',
                'title' => 'Who Really Owns Your AI? How to Protect Your Brand and Your Ideas',
                'speaker' => 'Amber Sheppard',
                'title_line' => 'Intellectual Property Attorney',
                'organization' => 'Pugh Accardo',
                'session_type' => 'Talk',
                'start_time' => '11:00:00',
                'end_time' => '11:45:00',
                'location' => 'Breakout Room 2',
                'description' => 'An IP-focused session on protecting ideas, brands, and ownership in an AI-enabled environment.',
            ],
            [
                'day' => 'day2',
                'track' => 'breakout_3',
                'title' => 'Grounded Futures, Shared Innovation: Using Suno AI to Transform Teaching and Learning',
                'speaker' => 'Shivochie Dinkins',
                'title_line' => 'Technology Specialist',
                'organization' => 'Yazoo County School District',
                'session_type' => 'Talk',
                'start_time' => '11:00:00',
                'end_time' => '11:45:00',
                'location' => 'Breakout Room 3',
                'description' => 'A teaching and learning session on applying Suno AI in grounded, collaborative ways.',
            ],

            [
                'day' => 'day2',
                'track' => 'ballroom_a',
                'title' => 'Break / Transition',
                'speaker' => null,
                'session_type' => 'Break',
                'start_time' => '11:45:00',
                'end_time' => '12:00:00',
                'location' => 'Ballroom A',
                'description' => 'Short transition break before lunch keynote.',
            ],
            [
                'day' => 'day2',
                'track' => 'ballroom_a',
                'title' => 'Lunch Keynote - Dr. Loretta Moore',
                'speaker' => 'Dr. Loretta Moore',
                'title_line' => 'Keynote Speaker',
                'organization' => 'Southern Spark',
                'session_type' => 'Keynote',
                'start_time' => '12:00:00',
                'end_time' => '13:00:00',
                'location' => 'Ballroom A',
                'description' => 'Lunch keynote address from Dr. Loretta Moore.',
                'is_featured' => true,
            ],

            [
                'day' => 'day2',
                'track' => 'ballroom_a',
                'title' => 'Open Networking',
                'speaker' => null,
                'session_type' => 'Networking',
                'start_time' => '13:00:00',
                'end_time' => '13:45:00',
                'location' => 'Ballroom A',
                'description' => 'Open networking time for attendee conversations, sponsor stops, and hallway meetings.',
            ],
            [
                'day' => 'day2',
                'track' => 'ballroom_b',
                'title' => 'Building Regional AI Innovation Ecosystems Alongside Startups and Founders',
                'speaker' => 'David Collins',
                'title_line' => 'Founder',
                'organization' => 'Southern Spark',
                'session_type' => 'Talk',
                'start_time' => '13:00:00',
                'end_time' => '13:45:00',
                'location' => 'Ballroom B',
                'description' => 'A founder-focused conversation about building regional AI ecosystems alongside startups and founders.',
            ],
            [
                'day' => 'day2',
                'track' => 'breakout_1',
                'title' => 'Governance Before Scale: Three Real-World AI Use Cases Designed for Responsible Deployment in Mississippi',
                'speaker' => 'Tianne Brown',
                'title_line' => 'CEO and Founder',
                'organization' => 'The Royce Group',
                'session_type' => 'Talk',
                'start_time' => '13:00:00',
                'end_time' => '13:45:00',
                'location' => 'Breakout Room 1',
                'description' => 'A governance-first look at responsible AI deployment through real-world Mississippi use cases.',
            ],
            [
                'day' => 'day2',
                'track' => 'breakout_2',
                'title' => 'From Rural Classrooms to Regional Talent Pipelines: Implementing AI and Data Literacy with Quanthub in the South',
                'speaker' => 'Belinda Patton and Stephanie Triplett',
                'title_line' => 'Quanthub Leaders',
                'organization' => 'Quanthub',
                'session_type' => 'Talk',
                'start_time' => '13:00:00',
                'end_time' => '13:45:00',
                'location' => 'Breakout Room 2',
                'description' => 'A session on using AI and data literacy to connect classrooms to talent pipeline development.',
            ],
            [
                'day' => 'day2',
                'track' => 'breakout_3',
                'title' => 'Building AI Access County by County: A Mississippi Case Study',
                'speaker' => 'Lara Taylor',
                'title_line' => 'Founder',
                'organization' => 'State AI Strategies',
                'session_type' => 'Talk',
                'start_time' => '13:00:00',
                'end_time' => '13:45:00',
                'location' => 'Breakout Room 3',
                'description' => 'A Mississippi-focused case study on scaling AI access county by county.',
            ],

            [
                'day' => 'day2',
                'track' => 'ballroom_a',
                'title' => 'Spark Chat Break',
                'speaker' => null,
                'session_type' => 'Break',
                'start_time' => '13:45:00',
                'end_time' => '13:55:00',
                'location' => 'Ballroom A',
                'description' => 'Short Spark Chat break between afternoon sessions.',
            ],

            [
                'day' => 'day2',
                'track' => 'ballroom_a',
                'title' => 'Open Networking',
                'speaker' => null,
                'session_type' => 'Networking',
                'start_time' => '13:55:00',
                'end_time' => '14:40:00',
                'location' => 'Ballroom A',
                'description' => 'Open networking time for attendee conversations, sponsor stops, and hallway meetings.',
            ],
            [
                'day' => 'day2',
                'track' => 'ballroom_b',
                'title' => 'Hidden Curriculum of Innovation',
                'speaker' => 'Delaney Foster and Katerina Sergi',
                'title_line' => 'Innovation and Research Leaders',
                'organization' => 'Mississippi State University',
                'session_type' => 'Talk',
                'start_time' => '13:55:00',
                'end_time' => '14:40:00',
                'location' => 'Ballroom B',
                'description' => 'A session on the often-unseen systems and behaviors that shape innovation culture.',
            ],
            [
                'day' => 'day2',
                'track' => 'breakout_1',
                'title' => 'AI Doesn\'t Fix Weak Strategy: Why Product Thinking Is the South\'s Missing AI Skill',
                'speaker' => 'Trevor Acy',
                'title_line' => 'Founder and Fractional Product Leader',
                'organization' => 'Delta Product Group',
                'session_type' => 'Talk',
                'start_time' => '13:55:00',
                'end_time' => '14:40:00',
                'location' => 'Breakout Room 1',
                'description' => 'A candid session on why product thinking matters more than hype when building with AI.',
            ],
            [
                'day' => 'day2',
                'track' => 'breakout_2',
                'title' => 'What AI Makes Possible for Social Equity in the South',
                'speaker' => 'Sonia Daniels, PhD',
                'title_line' => 'Founder and CEO',
                'organization' => 'S. Daniels Consulting',
                'session_type' => 'Talk',
                'start_time' => '13:55:00',
                'end_time' => '14:40:00',
                'location' => 'Breakout 2',
                'description' => 'A social equity session on what AI can unlock across Southern communities.',
            ],
            [
                'day' => 'day2',
                'track' => 'breakout_3',
                'title' => 'What Does It Take to Build a Regional Tech Hub?',
                'speaker' => 'Raymonda Placeholder and Nashlie Placeholder',
                'title_line' => 'Regional Innovation Speakers',
                'organization' => 'Southern Spark',
                'session_type' => 'Talk',
                'start_time' => '13:55:00',
                'end_time' => '14:40:00',
                'location' => 'Breakout Room 3',
                'description' => 'A regional innovation conversation about what it takes to build a stronger tech hub.',
            ],

            [
                'day' => 'day2',
                'track' => 'ballroom_a',
                'title' => 'Spark Chat Break',
                'speaker' => null,
                'session_type' => 'Break',
                'start_time' => '14:40:00',
                'end_time' => '14:50:00',
                'location' => 'Ballroom A',
                'description' => 'Short Spark Chat break before the final session block.',
            ],

            [
                'day' => 'day2',
                'track' => 'ballroom_a',
                'title' => 'Open Networking',
                'speaker' => null,
                'session_type' => 'Networking',
                'start_time' => '14:50:00',
                'end_time' => '15:35:00',
                'location' => 'Ballroom A',
                'description' => 'Open networking time for attendee conversations, sponsor stops, and hallway meetings.',
            ],
            [
                'day' => 'day2',
                'track' => 'ballroom_b',
                'title' => 'Code the Beat: Music, Messaging, and Mississippi\'s Next Coding DJ Competition',
                'speaker' => 'William "Edd" Blake and Tiffany Henderson',
                'title_line' => 'CSTA Chapter Leaders',
                'organization' => 'CSTA',
                'session_type' => 'Talk',
                'start_time' => '14:50:00',
                'end_time' => '15:35:00',
                'location' => 'Ballroom B',
                'description' => 'A creative session on coding, music, messaging, and engaging the next generation of technologists.',
            ],
            [
                'day' => 'day2',
                'track' => 'breakout_1',
                'title' => 'JSU Social Impact Challenge',
                'speaker' => 'Alisa Mosley',
                'title_line' => 'Speaker',
                'organization' => 'Jackson State University',
                'session_type' => 'Challenge Showcase',
                'start_time' => '14:50:00',
                'end_time' => '15:35:00',
                'location' => 'Breakout 1',
                'description' => 'Jackson State University social impact challenge session.',
            ],
            [
                'day' => 'day2',
                'track' => 'breakout_3',
                'title' => 'AI & Disfluency',
                'speaker' => 'Craig A. Meyer',
                'title_line' => 'Speaker',
                'organization' => 'Southern Spark',
                'session_type' => 'Talk',
                'start_time' => '14:50:00',
                'end_time' => '15:35:00',
                'location' => 'Breakout 3',
                'description' => 'A session exploring AI and disfluency in a community and policy context.',
            ],

            [
                'day' => 'day2',
                'track' => 'ballroom_a',
                'title' => 'Break / Transition',
                'speaker' => null,
                'session_type' => 'Break',
                'start_time' => '15:35:00',
                'end_time' => '15:45:00',
                'location' => 'Ballroom A',
                'description' => 'Short transition break before the closing conversation.',
            ],
            [
                'day' => 'day2',
                'track' => 'ballroom_a',
                'title' => 'Final Spark Chat: Where Do We Go Next? Building Mississippi\'s AI Ecosystem',
                'speaker' => 'Krystal Chatman',
                'title_line' => 'Moderator',
                'organization' => 'Southern Spark',
                'session_type' => 'Plenary',
                'start_time' => '15:45:00',
                'end_time' => '16:30:00',
                'location' => 'Ballroom A',
                'description' => 'A closing conversation about the future of Mississippi\'s AI ecosystem.',
                'is_featured' => true,
            ],
        ];

        foreach ($sessionData as $item) {
            $speakerId = null;

            if (! empty($item['speaker'])) {
                $key = $item['speaker'];

                if (! isset($speakers[$key])) {
                    $speakers[$key] = Speaker::create([
                        'full_name' => $item['speaker'],
                        'title' => $item['title_line'] ?? 'Speaker',
                        'organization' => $item['organization'] ?? 'Southern Spark',
                        'bio' => $item['description'],
                    ]);
                }

                $speakerId = $speakers[$key]->id;
            }

            Session::create([
                'day_id' => $days[$item['day']]->id,
                'track_id' => $tracks[$item['track']]->id,
                'speaker_id' => $speakerId,
                'title' => $item['title'],
                'description' => $item['description'],
                'session_type' => $item['session_type'],
                'start_time' => $item['start_time'],
                'end_time' => $item['end_time'],
                'location' => $item['location'],
                'is_featured' => $item['is_featured'] ?? false,
            ]);
        }

        Announcement::create([
            'title' => 'Southern Spark Conference App Is Live',
            'message' => 'Browse the full two-day agenda, explore speakers, and save sessions to your personal schedule.',
        ]);

        Announcement::create([
            'title' => 'June 11-12 Agenda Loaded',
            'message' => 'The updated June 11-12 conference schedule is now seeded across Ballroom A, Ballroom B, and the breakout rooms.',
        ]);

        Announcement::create([
            'title' => 'Sponsors Coming Soon',
            'message' => 'Sponsor placements and profile enhancements are ready, with support for up to twenty sponsors in the app.',
        ]);
    }

    private function createTrack(string $name, string $description): Track
    {
        return Track::create([
            'name' => $name,
            'description' => $description,
        ]);
    }

    private function sharedSessions(
        string $day,
        array $tracks,
        string $title,
        string $sessionType,
        string $startTime,
        string $endTime,
        string $description,
        ?string $location = null
    ): array {
        return collect($tracks)
            ->map(fn (string $track) => [
                'day' => $day,
                'track' => $track,
                'title' => $title,
                'speaker' => null,
                'session_type' => $sessionType,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'location' => $location ?? $this->trackLabel($track),
                'description' => $description,
            ])
            ->all();
    }

    private function sharedFeature(
        string $day,
        array $tracks,
        string $title,
        string $sessionType,
        string $startTime,
        string $endTime,
        string $description,
        ?string $location = null
    ): array {
        return collect($this->sharedSessions($day, $tracks, $title, $sessionType, $startTime, $endTime, $description, $location))
            ->map(fn (array $session) => $session + ['is_featured' => true])
            ->all();
    }

    private function sharedKeynote(
        string $day,
        array $tracks,
        string $title,
        string $speaker,
        string $titleLine,
        string $organization,
        string $startTime,
        string $endTime,
        string $description
    ): array {
        return collect($tracks)
            ->map(fn (string $track) => [
                'day' => $day,
                'track' => $track,
                'title' => $title,
                'speaker' => $speaker,
                'title_line' => $titleLine,
                'organization' => $organization,
                'session_type' => 'Keynote',
                'start_time' => $startTime,
                'end_time' => $endTime,
                'location' => $this->trackLabel($track),
                'description' => $description,
                'is_featured' => true,
            ])
            ->all();
    }

    private function sharedPlenary(
        string $day,
        array $tracks,
        string $title,
        string $speaker,
        string $titleLine,
        string $organization,
        string $startTime,
        string $endTime,
        string $description
    ): array {
        return collect($tracks)
            ->map(fn (string $track) => [
                'day' => $day,
                'track' => $track,
                'title' => $title,
                'speaker' => $speaker,
                'title_line' => $titleLine,
                'organization' => $organization,
                'session_type' => 'Plenary',
                'start_time' => $startTime,
                'end_time' => $endTime,
                'location' => $this->trackLabel($track),
                'description' => $description,
                'is_featured' => true,
            ])
            ->all();
    }

    private function trackLabel(string $track): string
    {
        return match ($track) {
            'ballroom_a' => 'Ballroom A',
            'ballroom_b' => 'Ballroom B',
            'breakout_1' => 'Breakout 1',
            'breakout_2' => 'Breakout 2',
            'breakout_3' => 'Breakout 3',
            default => 'Conference Room',
        };
    }
}
