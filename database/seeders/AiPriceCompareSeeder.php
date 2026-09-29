<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Deal;
use App\Models\ExternalLink;
use App\Models\Feature;
use App\Models\Plan;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductFeature;
use App\Models\Source;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AiPriceCompareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@aipricecompare.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        // 2. Create Target Countries
        $usa = Country::updateOrCreate(
            ['code' => 'USA'],
            [
                'name' => 'United States',
                'currency_code' => 'USD',
                'currency_symbol' => '$',
                'is_active' => true,
            ]
        );

        $australia = Country::updateOrCreate(
            ['code' => 'AU'],
            [
                'name' => 'Australia',
                'currency_code' => 'AUD',
                'currency_symbol' => 'A$',
                'is_active' => true,
            ]
        );

        $india = Country::updateOrCreate(
            ['code' => 'IN'],
            [
                'name' => 'India',
                'currency_code' => 'INR',
                'currency_symbol' => '₹',
                'is_active' => true,
            ]
        );

        $uk = Country::updateOrCreate(
            ['code' => 'UK'],
            [
                'name' => 'United Kingdom',
                'currency_code' => 'GBP',
                'currency_symbol' => '£',
                'is_active' => true,
            ]
        );

        // 3. Create Features Catalog
        $featuresList = [
            [
                'name' => 'Coding Assistance',
                'slug' => 'coding',
                'category' => 'Development',
                'description' => 'Code generation, debugging, refactoring, and IDE completion.',
            ],
            [
                'name' => 'Writing & Copywriting',
                'slug' => 'writing',
                'category' => 'Content',
                'description' => 'Essays, emails, creative writing, and documentation drafting.',
            ],
            [
                'name' => 'Web & Academic Research',
                'slug' => 'research',
                'category' => 'Search',
                'description' => 'Real-time live internet search and source citations.',
            ],
            [
                'name' => 'Image Generation',
                'slug' => 'image-generation',
                'category' => 'Media',
                'description' => 'Create images from text prompts (DALL-E, Imagen, Flux, Midjourney API).',
            ],
            [
                'name' => 'File & Document Analysis',
                'slug' => 'file-upload',
                'category' => 'Productivity',
                'description' => 'Upload PDFs, CSVs, images, and text documents for analysis.',
            ],
            [
                'name' => 'Real-time Voice Chat',
                'slug' => 'voice',
                'category' => 'Interaction',
                'description' => 'Natural conversational voice mode input and output.',
            ],
            [
                'name' => 'Developer API Access',
                'slug' => 'api',
                'category' => 'Development',
                'description' => 'Access model endpoint APIs for integration into custom software.',
            ],
            [
                'name' => 'Advanced Reasoning Models',
                'slug' => 'reasoning',
                'category' => 'Intelligence',
                'description' => 'Step-by-step chain-of-thought reasoning models (OpenAI o1/o3, Claude Thinking, DeepSeek R1).',
            ],
            [
                'name' => 'Data Analysis & Code Interpreter',
                'slug' => 'data-analysis',
                'category' => 'Productivity',
                'description' => 'Execute Python code sandbox for data analysis and charts.',
            ],
        ];

        $featureModels = [];
        foreach ($featuresList as $f) {
            $featureModels[$f['slug']] = Feature::updateOrCreate(['slug' => $f['slug']], $f);
        }

        // 4. Create 10 Real-World AI Products & Verified Plans
        $verifiedDate = '2026-09-29';

        $productsData = [
            [
                'name' => 'ChatGPT',
                'slug' => 'chatgpt',
                'company_name' => 'OpenAI',
                'description' => 'Leading conversational AI powered by GPT-4o and o3 reasoning models for coding, writing, research, and voice.',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/0/04/ChatGPT_logo.svg',
                'official_url' => 'https://chatgpt.com',
                'plans' => [
                    [
                        'name' => 'Free',
                        'slug' => 'free',
                        'description' => 'Access to GPT-4o mini with limited GPT-4o usage and web browsing.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://openai.com/chatgpt/pricing/'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://openai.com/chatgpt/pricing/'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://openai.com/chatgpt/pricing/'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://openai.com/chatgpt/pricing/'],
                        ],
                    ],
                    [
                        'name' => 'Plus',
                        'slug' => 'plus',
                        'description' => 'Full access to GPT-4o, o3-mini reasoning, DALL-E 3 image generation, Advanced Voice, and custom GPTs.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 20.00, 'billing_period' => 'monthly', 'source' => 'https://openai.com/chatgpt/pricing/'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 33.00, 'billing_period' => 'monthly', 'source' => 'https://openai.com/chatgpt/pricing/'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 1999.00, 'billing_period' => 'monthly', 'source' => 'https://openai.com/chatgpt/pricing/'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 16.00, 'billing_period' => 'monthly', 'source' => 'https://openai.com/chatgpt/pricing/'],
                        ],
                    ],
                    [
                        'name' => 'Pro',
                        'slug' => 'pro',
                        'description' => 'Unlimited access to all models including o1 pro mode and maximum compute capabilities.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 200.00, 'billing_period' => 'monthly', 'source' => 'https://openai.com/chatgpt/pricing/'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 330.00, 'billing_period' => 'monthly', 'source' => 'https://openai.com/chatgpt/pricing/'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 19900.00, 'billing_period' => 'monthly', 'source' => 'https://openai.com/chatgpt/pricing/'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 160.00, 'billing_period' => 'monthly', 'source' => 'https://openai.com/chatgpt/pricing/'],
                        ],
                    ],
                ],
                'features' => [
                    'coding' => true,
                    'writing' => true,
                    'research' => true,
                    'image-generation' => true,
                    'file-upload' => true,
                    'voice' => true,
                    'api' => true,
                    'reasoning' => true,
                    'data-analysis' => true,
                ],
            ],
            [
                'name' => 'Claude',
                'slug' => 'claude',
                'company_name' => 'Anthropic',
                'description' => 'High-intelligence AI assistant powered by Claude 3.5 Sonnet and Claude 3.5 Haiku, renowned for coding precision and long context window.',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/7/78/Anthropic_logo.svg',
                'official_url' => 'https://claude.ai',
                'plans' => [
                    [
                        'name' => 'Free',
                        'slug' => 'free',
                        'description' => 'Access to Claude on web and mobile with daily rate limits.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.anthropic.com/pricing'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.anthropic.com/pricing'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.anthropic.com/pricing'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.anthropic.com/pricing'],
                        ],
                    ],
                    [
                        'name' => 'Pro',
                        'slug' => 'pro',
                        'description' => '5x usage compared to free tier, priority access to Claude 3.5 Sonnet, Projects workspace, and artifacts.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 20.00, 'billing_period' => 'monthly', 'source' => 'https://www.anthropic.com/pricing'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 33.00, 'billing_period' => 'monthly', 'source' => 'https://www.anthropic.com/pricing'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 1999.00, 'billing_period' => 'monthly', 'source' => 'https://www.anthropic.com/pricing'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 16.00, 'billing_period' => 'monthly', 'source' => 'https://www.anthropic.com/pricing'],
                        ],
                    ],
                ],
                'features' => [
                    'coding' => true,
                    'writing' => true,
                    'research' => true,
                    'image-generation' => false,
                    'file-upload' => true,
                    'voice' => false,
                    'api' => true,
                    'reasoning' => true,
                    'data-analysis' => true,
                ],
            ],
            [
                'name' => 'Gemini',
                'slug' => 'gemini',
                'company_name' => 'Google',
                'description' => 'Multimodal AI platform by Google integrated with Google Workspace, YouTube, Maps, and deep reasoning 1.5 Pro models.',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/8/8a/Google_Gemini_logo.svg',
                'official_url' => 'https://gemini.google.com',
                'plans' => [
                    [
                        'name' => 'Free',
                        'slug' => 'free',
                        'description' => 'Access to Gemini Flash model for quick answers and multimodal assistance.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://one.google.com/explore-plan/gemini-advanced'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://one.google.com/explore-plan/gemini-advanced'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://one.google.com/explore-plan/gemini-advanced'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://one.google.com/explore-plan/gemini-advanced'],
                        ],
                    ],
                    [
                        'name' => 'Advanced (Google One AI)',
                        'slug' => 'advanced',
                        'description' => 'Access to Gemini 1.5 Pro with 1M context window, Imagen 3 generation, 2TB Google storage, and Docs integration.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 19.99, 'billing_period' => 'monthly', 'source' => 'https://one.google.com/explore-plan/gemini-advanced'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 32.99, 'billing_period' => 'monthly', 'source' => 'https://one.google.com/explore-plan/gemini-advanced'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 1950.00, 'billing_period' => 'monthly', 'source' => 'https://one.google.com/explore-plan/gemini-advanced'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 18.99, 'billing_period' => 'monthly', 'source' => 'https://one.google.com/explore-plan/gemini-advanced'],
                        ],
                    ],
                ],
                'features' => [
                    'coding' => true,
                    'writing' => true,
                    'research' => true,
                    'image-generation' => true,
                    'file-upload' => true,
                    'voice' => true,
                    'api' => true,
                    'reasoning' => true,
                    'data-analysis' => true,
                ],
            ],
            [
                'name' => 'Grok',
                'slug' => 'grok',
                'company_name' => 'xAI',
                'description' => 'Real-time conversational AI built by xAI with direct integration to X (formerly Twitter) live news, uninhibited search, and image analysis.',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/57/X_logo_2023.svg',
                'official_url' => 'https://x.ai',
                'plans' => [
                    [
                        'name' => 'Free',
                        'slug' => 'free',
                        'description' => 'Basic query access on web and X.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://x.ai'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://x.ai'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://x.ai'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://x.ai'],
                        ],
                    ],
                    [
                        'name' => 'Premium',
                        'slug' => 'premium',
                        'description' => 'Grok 2 access via X Premium membership with real-time news search and Flux image creation.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 8.00, 'billing_period' => 'monthly', 'source' => 'https://x.com/i/premium_sign_up'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 13.00, 'billing_period' => 'monthly', 'source' => 'https://x.com/i/premium_sign_up'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 650.00, 'billing_period' => 'monthly', 'source' => 'https://x.com/i/premium_sign_up'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 6.00, 'billing_period' => 'monthly', 'source' => 'https://x.com/i/premium_sign_up'],
                        ],
                    ],
                ],
                'features' => [
                    'coding' => true,
                    'writing' => true,
                    'research' => true,
                    'image-generation' => true,
                    'file-upload' => true,
                    'voice' => false,
                    'api' => true,
                    'reasoning' => true,
                    'data-analysis' => true,
                ],
            ],
            [
                'name' => 'Perplexity',
                'slug' => 'perplexity',
                'company_name' => 'Perplexity AI',
                'description' => 'AI-powered conversational search engine delivering concise answers backed by inline web source citations.',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/1/1d/Perplexity_AI_logo.svg',
                'official_url' => 'https://www.perplexity.ai',
                'plans' => [
                    [
                        'name' => 'Free',
                        'slug' => 'free',
                        'description' => 'Unlimited quick searches and 5 Pro searches per day.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.perplexity.ai/pro'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.perplexity.ai/pro'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.perplexity.ai/pro'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.perplexity.ai/pro'],
                        ],
                    ],
                    [
                        'name' => 'Pro',
                        'slug' => 'pro',
                        'description' => '300+ Pro searches daily, access to GPT-4o, Claude 3.5 Sonnet, Sonar Reasoning models, file uploads, and $5 monthly API credit.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 20.00, 'billing_period' => 'monthly', 'source' => 'https://www.perplexity.ai/pro'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 33.00, 'billing_period' => 'monthly', 'source' => 'https://www.perplexity.ai/pro'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 1999.00, 'billing_period' => 'monthly', 'source' => 'https://www.perplexity.ai/pro'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 16.00, 'billing_period' => 'monthly', 'source' => 'https://www.perplexity.ai/pro'],
                        ],
                    ],
                ],
                'features' => [
                    'coding' => true,
                    'writing' => true,
                    'research' => true,
                    'image-generation' => true,
                    'file-upload' => true,
                    'voice' => false,
                    'api' => true,
                    'reasoning' => true,
                    'data-analysis' => true,
                ],
            ],
            [
                'name' => 'Microsoft Copilot',
                'slug' => 'microsoft-copilot',
                'company_name' => 'Microsoft',
                'description' => 'AI companion built into Windows and Microsoft 365, combining GPT-4o intelligence with web search and Office application integration.',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/2/2a/Microsoft_Copilot_Icon.svg',
                'official_url' => 'https://copilot.microsoft.com',
                'plans' => [
                    [
                        'name' => 'Free',
                        'slug' => 'free',
                        'description' => 'Standard web chat and Designer image creation on Bing and Windows.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.microsoft.com/copilot/copilot-pro'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.microsoft.com/copilot/copilot-pro'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.microsoft.com/copilot/copilot-pro'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.microsoft.com/copilot/copilot-pro'],
                        ],
                    ],
                    [
                        'name' => 'Copilot Pro',
                        'slug' => 'copilot-pro',
                        'description' => 'Priority access to GPT-4o during peak times, integration with Word, Excel, PowerPoint, and faster image generation.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 20.00, 'billing_period' => 'monthly', 'source' => 'https://www.microsoft.com/copilot/copilot-pro'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 33.00, 'billing_period' => 'monthly', 'source' => 'https://www.microsoft.com/copilot/copilot-pro'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 2000.00, 'billing_period' => 'monthly', 'source' => 'https://www.microsoft.com/copilot/copilot-pro'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 19.00, 'billing_period' => 'monthly', 'source' => 'https://www.microsoft.com/copilot/copilot-pro'],
                        ],
                    ],
                ],
                'features' => [
                    'coding' => true,
                    'writing' => true,
                    'research' => true,
                    'image-generation' => true,
                    'file-upload' => true,
                    'voice' => true,
                    'api' => true,
                    'reasoning' => true,
                    'data-analysis' => true,
                ],
            ],
            [
                'name' => 'DeepSeek',
                'slug' => 'deepseek',
                'company_name' => 'DeepSeek AI',
                'description' => 'State-of-the-art open-weights reasoning model DeepSeek-R1 and DeepSeek-V3, offering ultra low-cost API inference and free web chat.',
                'logo_url' => 'https://chat.deepseek.com/favicon.ico',
                'official_url' => 'https://chat.deepseek.com',
                'plans' => [
                    [
                        'name' => 'Free (Web & App)',
                        'slug' => 'free',
                        'description' => 'Unlimited web and mobile access to DeepSeek-V3 and DeepSeek-R1 reasoning models.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.deepseek.com'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.deepseek.com'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.deepseek.com'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.deepseek.com'],
                        ],
                    ],
                ],
                'features' => [
                    'coding' => true,
                    'writing' => true,
                    'research' => true,
                    'image-generation' => false,
                    'file-upload' => true,
                    'voice' => false,
                    'api' => true,
                    'reasoning' => true,
                    'data-analysis' => true,
                ],
            ],
            [
                'name' => 'Cursor AI',
                'slug' => 'cursor',
                'company_name' => 'Anysphere',
                'description' => 'AI-first code editor built on VS Code with agentic multi-file editing, terminal integration, and instant codebase context.',
                'logo_url' => 'https://www.cursor.com/favicon.ico',
                'official_url' => 'https://www.cursor.com',
                'plans' => [
                    [
                        'name' => 'Hobby',
                        'slug' => 'hobby',
                        'description' => 'Free 14-day Pro trial, 2000 completions, and 50 slow premium requests.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.cursor.com/pricing'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.cursor.com/pricing'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.cursor.com/pricing'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://www.cursor.com/pricing'],
                        ],
                    ],
                    [
                        'name' => 'Pro',
                        'slug' => 'pro',
                        'description' => '500 fast premium requests/mo (Claude 3.5 Sonnet & GPT-4o), unlimited slow requests, and 10 o1 uses.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 20.00, 'billing_period' => 'monthly', 'source' => 'https://www.cursor.com/pricing'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 33.00, 'billing_period' => 'monthly', 'source' => 'https://www.cursor.com/pricing'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 1999.00, 'billing_period' => 'monthly', 'source' => 'https://www.cursor.com/pricing'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 16.00, 'billing_period' => 'monthly', 'source' => 'https://www.cursor.com/pricing'],
                        ],
                    ],
                ],
                'features' => [
                    'coding' => true,
                    'writing' => false,
                    'research' => false,
                    'image-generation' => false,
                    'file-upload' => true,
                    'voice' => false,
                    'api' => true,
                    'reasoning' => true,
                    'data-analysis' => true,
                ],
            ],
            [
                'name' => 'Mistral AI',
                'slug' => 'mistral',
                'company_name' => 'Mistral AI',
                'description' => 'European frontier AI lab creator of Mistral Large 2, Pixtral, and Le Chat conversational workspace.',
                'logo_url' => 'https://mistral.ai/favicon.ico',
                'official_url' => 'https://mistral.ai',
                'plans' => [
                    [
                        'name' => 'Le Chat Free',
                        'slug' => 'free',
                        'description' => 'Free access to Mistral Large 2, web search, Pixtral image analysis, and code execution.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://chat.mistral.ai/'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://chat.mistral.ai/'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://chat.mistral.ai/'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://chat.mistral.ai/'],
                        ],
                    ],
                ],
                'features' => [
                    'coding' => true,
                    'writing' => true,
                    'research' => true,
                    'image-generation' => true,
                    'file-upload' => true,
                    'voice' => false,
                    'api' => true,
                    'reasoning' => true,
                    'data-analysis' => true,
                ],
            ],
            [
                'name' => 'Poe',
                'slug' => 'poe',
                'company_name' => 'Quora',
                'description' => 'All-in-one AI ecosystem subscription allowing access to GPT-4o, Claude 3.5 Sonnet, Midjourney, and FLUX bots under a single monthly plan.',
                'logo_url' => 'https://poe.com/favicon.ico',
                'official_url' => 'https://poe.com',
                'plans' => [
                    [
                        'name' => 'Free',
                        'slug' => 'free',
                        'description' => 'Daily free points to query basic bots and models.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://poe.com/subscribe'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://poe.com/subscribe'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://poe.com/subscribe'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 0.00, 'billing_period' => 'free', 'source' => 'https://poe.com/subscribe'],
                        ],
                    ],
                    [
                        'name' => 'Subscriber',
                        'slug' => 'subscriber',
                        'description' => '1,000,000 monthly compute points for access to all top-tier models and custom bot creation.',
                        'prices' => [
                            ['country' => $usa, 'currency' => 'USD', 'price' => 19.99, 'billing_period' => 'monthly', 'source' => 'https://poe.com/subscribe'],
                            ['country' => $australia, 'currency' => 'AUD', 'price' => 32.99, 'billing_period' => 'monthly', 'source' => 'https://poe.com/subscribe'],
                            ['country' => $india, 'currency' => 'INR', 'price' => 1999.00, 'billing_period' => 'monthly', 'source' => 'https://poe.com/subscribe'],
                            ['country' => $uk, 'currency' => 'GBP', 'price' => 16.00, 'billing_period' => 'monthly', 'source' => 'https://poe.com/subscribe'],
                        ],
                    ],
                ],
                'features' => [
                    'coding' => true,
                    'writing' => true,
                    'research' => true,
                    'image-generation' => true,
                    'file-upload' => true,
                    'voice' => false,
                    'api' => true,
                    'reasoning' => true,
                    'data-analysis' => true,
                ],
            ],
        ];

        $productModels = [];

        foreach ($productsData as $pData) {
            $product = Product::updateOrCreate(
                ['slug' => $pData['slug']],
                [
                    'name' => $pData['name'],
                    'company_name' => $pData['company_name'],
                    'description' => $pData['description'],
                    'logo_url' => $pData['logo_url'],
                    'official_url' => $pData['official_url'],
                    'status' => 'active',
                ]
            );

            $productModels[$pData['slug']] = $product;

            // External link record
            ExternalLink::updateOrCreate(
                ['product_id' => $product->id, 'type' => 'official'],
                [
                    'country_id' => null,
                    'url' => $product->official_url,
                    'status' => 'active',
                ]
            );

            // Seed Plans & Prices
            foreach ($pData['plans'] as $planData) {
                $plan = Plan::updateOrCreate(
                    ['product_id' => $product->id, 'slug' => $planData['slug']],
                    [
                        'name' => $planData['name'],
                        'description' => $planData['description'],
                        'status' => 'active',
                    ]
                );

                foreach ($planData['prices'] as $prData) {
                    $price = Price::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'plan_id' => $plan->id,
                            'country_id' => $prData['country']->id,
                        ],
                        [
                            'currency' => $prData['currency'],
                            'price' => $prData['price'],
                            'billing_period' => $prData['billing_period'],
                            'source_url' => $prData['source'],
                            'verified_at' => $verifiedDate,
                        ]
                    );

                    Source::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'price_id' => $price->id,
                        ],
                        [
                            'source_url' => $prData['source'],
                            'source_type' => 'official',
                            'verified_at' => $verifiedDate,
                            'notes' => 'Official pricing page verified during launch.',
                        ]
                    );
                }
            }

            // Seed Product Features
            foreach ($pData['features'] as $fSlug => $isAvailable) {
                if (isset($featureModels[$fSlug])) {
                    ProductFeature::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'feature_id' => $featureModels[$fSlug]->id,
                        ],
                        [
                            'is_available' => $isAvailable,
                            'notes' => $isAvailable ? 'Supported' : 'Not available',
                        ]
                    );
                }
            }
        }

        // 5. Seed Deals & Promos Catalog
        $dealsList = [
            [
                'product_id' => $productModels['perplexity']->id ?? null,
                'title' => 'Perplexity Pro 1-Year Free Student Offer',
                'slug' => 'perplexity-pro-student-deal',
                'discount_type' => 'student',
                'deal_url' => 'https://www.perplexity.ai/pro',
                'description' => 'Eligible students with verified .edu university email addresses can receive 1 year of free Perplexity Pro membership.',
                'verified_at' => $verifiedDate,
                'status' => 'active',
            ],
            [
                'product_id' => $productModels['microsoft-copilot']->id ?? null,
                'title' => 'GitHub Student Developer Pack (Free Copilot)',
                'slug' => 'github-student-pack-copilot',
                'discount_type' => 'student',
                'deal_url' => 'https://education.github.com/pack',
                'description' => 'Free access to GitHub Copilot AI coding assistant for all verified university and college students.',
                'verified_at' => $verifiedDate,
                'status' => 'active',
            ],
            [
                'product_id' => $productModels['gemini']->id ?? null,
                'title' => 'Google Cloud $300 Student & Developer Credits',
                'slug' => 'google-cloud-student-credits',
                'discount_type' => 'api_credit',
                'deal_url' => 'https://cloud.google.com/free',
                'description' => '$300 in free cloud & Gemini API credits for student developers building LLM applications.',
                'verified_at' => $verifiedDate,
                'status' => 'active',
            ],
            [
                'product_id' => $productModels['chatgpt']->id ?? null,
                'title' => 'OpenAI Student Researcher Grants',
                'slug' => 'openai-student-research-grant',
                'discount_type' => 'api_credit',
                'deal_url' => 'https://openai.com/api/',
                'description' => 'Up to $2,500 in API credits for university students conducting academic research in artificial intelligence.',
                'verified_at' => $verifiedDate,
                'status' => 'active',
            ],
        ];

        foreach ($dealsList as $d) {
            Deal::updateOrCreate(['slug' => $d['slug']], $d);
        }
    }
}
