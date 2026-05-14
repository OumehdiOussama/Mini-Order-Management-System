<div id="chatbot-widget" class="fixed bottom-6 right-6 z-50 font-sans" dir="ltr" x-data="chatbot()">
    
    <!-- Chat Button -->
    <button @click="toggle()" 
            class="bg-blue-600 hover:bg-blue-700 text-white w-14 h-14 rounded-full shadow-2xl shadow-blue-500/50 flex items-center justify-center transition-all hover:scale-110 active:scale-95 focus:outline-none ring-4 ring-white dark:ring-slate-900">
        <svg x-show="!isOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
        </svg>
        <svg x-show="isOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <!-- Chat Window -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         class="fixed bottom-24 right-6 w-[calc(100vw-3rem)] sm:w-[448px] bg-white border border-slate-200 dark:border-slate-800 rounded-3xl shadow-2xl overflow-hidden flex flex-col z-[60] h-[480px] max-h-[calc(100vh-120px)]"
         x-cloak>
        
        <!-- Header -->
        <div class="bg-blue-600 p-4 text-white flex justify-between items-center">
            <div>
                <h3 class="font-bold text-base">OMS Support</h3>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse shadow-[0_0_8px_rgba(74,222,128,0.8)]"></span>
                    <p class="text-blue-100 text-[10px] font-bold">Always Online</p>
                </div>
            </div>
        </div>

        <!-- Messages Area -->
        <div id="chat-messages" 
             x-init="
                 const observer = new MutationObserver(() => {
                     $el.scrollTop = $el.scrollHeight;
                 });
                 observer.observe($el, { childList: true, subtree: true, characterData: true });
             "
             class="p-4 flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-950 flex flex-col space-y-3 min-h-0">
            <template x-for="msg in messages">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="msg.role === 'user' ? 'bg-blue-600 text-white rounded-2xl rounded-tr-none' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 rounded-2xl rounded-tl-none'"
                         class="max-w-[85%] p-3 shadow-sm text-xs leading-relaxed"
                         x-html="msg.content">
                    </div>
                </div>
            </template>
            <div x-show="isTyping" class="flex justify-start">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-3 rounded-2xl rounded-tl-none shadow-sm flex gap-1">
                    <span class="w-1 h-1 bg-slate-400 rounded-full animate-bounce"></span>
                    <span class="w-1 h-1 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                    <span class="w-1 h-1 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
            <div class="flex gap-2 mb-2">
                <input type="text" 
                       x-model="userInput" 
                       @keyup.enter="sendMessage()"
                       placeholder="Ask about orders, stock, or roles..." 
                       class="flex-1 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all dark:text-white">
                <button @click="sendMessage()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-xl transition-all shadow-lg shadow-blue-500/20 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
            
            <!-- Quick Suggestions -->
            <div class="flex flex-wrap gap-2 pt-1">
                <template x-for="s in suggestions">
                    <button @click="userInput = s; sendMessage()" 
                            class="text-[9px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 px-2 py-1 rounded-md hover:bg-blue-600 hover:text-white transition-all">
                        <span x-text="s"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
    function chatbot() {
        return {
            isOpen: false,
            isTyping: false,
            userInput: '',
            suggestions: ['track order', 'inventory', 'register', 'cancel order', 'notifications', 'mobile', 'analytics'],
            messages: [
                { role: 'bot', content: 'Hello! I am your OMS support assistant. I can help you with tracking, inventory, roles, or anything else about the platform. How can I help you today?' }
            ],
            
            toggle() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    this.$nextTick(() => {
                        const area = document.getElementById('chat-messages');
                        area.scrollTop = area.scrollHeight;
                    });
                }
            },

            async sendMessage() {
                if (!this.userInput.trim()) return;

                const query = this.userInput.trim();
                console.log('Chatbot received query:', query);
                
                this.messages.push({ role: 'user', content: query });
                this.userInput = '';
                
                this.scrollToBottom();
                this.isTyping = true;
                
                // Simulate thinking time
                await new Promise(r => setTimeout(r, 800));
                this.isTyping = false;
                
                const fullResponse = this.getResponse(query);
                const botIndex = this.messages.push({ role: 'bot', content: '' }) - 1;
                
                let currentContent = '';
                for (let i = 0; i < fullResponse.length; i++) {
                    // Handle HTML tags properly
                    if (fullResponse[i] === '<') {
                        const tagEnd = fullResponse.indexOf('>', i);
                        if (tagEnd !== -1) {
                            currentContent += fullResponse.substring(i, tagEnd + 1);
                            i = tagEnd;
                        } else {
                            currentContent += fullResponse[i];
                        }
                    } else {
                        currentContent += fullResponse[i];
                    }
                    
                    // Update state
                    this.messages[botIndex].content = currentContent;
                    
                    // Controlled delay for typewriter effect
                    await new Promise(r => setTimeout(r, 10));
                    this.scrollToBottom();
                }
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const area = document.getElementById('chat-messages');
                    if (area) area.scrollTop = area.scrollHeight;
                });
            },

            getResponse(query) {
                const q = query.toLowerCase();
                
                // --- Brand/Identity ---
                if (q.includes('oms') || q.includes('what is this') || q.includes('purpose')) {
                    return "<b>OMS</b> stands for <b>Order Management System</b>. It's a comprehensive platform designed to streamline how businesses handle sales, inventory, and fulfillment. This specific version features real-time tracking, multi-role access, and automated stock management.";
                }

                if (q.includes('ai') || q.includes('smart') || q.includes('intelligence')) {
                    return "I am a built-in assistant powered by logic to help you navigate this OMS. While I'm not a full LLM like Gemini, I'm designed to provide instant, accurate information about your orders and inventory.";
                }

                // --- Greetings & General ---
                if (q.includes('hello') || q.includes('hi') || q.includes('hey') || q.includes('yo')) {
                    return "Hello! I'm your OMS Assistant. I'm here to help you navigate the platform and manage your orders. What can I do for you today?";
                }
                
                if (q.includes('how are you')) {
                    return "I'm functioning at 100% efficiency! Ready to help you with your order management needs. How are you doing today?";
                }

                if (q.includes('thank') || q.includes('thanks')) {
                    return "You're very welcome! It's my pleasure to assist you. Is there anything else you'd like to know about the platform?";
                }

                if (q.includes('bye')) {
                    return "Goodbye! Have a productive day managing your orders. I'll be here if you need anything else!";
                }

                // --- Core Functionality ---
                if (q.includes('track') || q.includes('status') || q.includes('where is')) {
                    return "To track an order, simply log in and head to your <b>Dashboard</b>. In the 'Orders' section, you can see real-time statuses like <b>Pending</b>, <b>Shipped</b>, or <b>Delivered</b>, along with a complete event timeline.";
                }
                
                if (q.includes('inventory') || q.includes('stock') || q.includes('product') || q.includes('item')) {
                    return "The system uses <b>Atomic Stock Management</b>. When an order is created, stock is instantly deducted. If an order is cancelled, the system automatically restores those items to ensure your inventory is always accurate.";
                }

                if (q.includes('register') || q.includes('account') || q.includes('join')) {
                    return "You can register by clicking the <b>Join Now</b> button in the header. We offer dedicated portals for both <b>Customers</b> (to place orders) and <b>Admins</b> (to manage the entire system).";
                }

                if (q.includes('role') || q.includes('admin') || q.includes('permission')) {
                    return "This OMS uses <b>Role-Based Access Control (RBAC)</b>. Admins have a birds-eye view of all operations, while Customers have a streamlined interface focused purely on their personal orders and tracking.";
                }

                if (q.includes('cancel')) {
                    return "Orders can be cancelled through the dashboard if they haven't been shipped yet. Cancellation triggers an automatic inventory restoration for all included products.";
                }

                if (q.includes('notification') || q.includes('email') || q.includes('alert')) {
                    return "The platform features a real-time notification engine. You'll receive instant alerts on your dashboard whenever an order moves through its lifecycle (e.g., from 'Processing' to 'Shipped').";
                }

                if (q.includes('mobile') || q.includes('phone') || q.includes('responsive')) {
                    return "Yes! The entire platform is built with modern, responsive design principles. It works perfectly on smartphones, tablets, and desktops.";
                }

                if (q.includes('analytic') || q.includes('chart') || q.includes('report')) {
                    return "Admins have access to powerful <b>Visual Analytics</b>. The dashboard includes charts for sales trends, category distribution, and order volume over time.";
                }

                // --- Support & Help ---
                if (q.includes('help') || q.includes('problem') || q.includes('stuck')) {
                    return "I'm here to help! You can ask me about: <b>tracking</b>, <b>stock management</b>, <b>user roles</b>, or <b>cancellations</b>. What's bothering you?";
                }

                if (q.includes('search') || q.includes('find')) {
                    return "You can use the <b>Smart Filter Bar</b> on any management page to quickly find specific orders, customers, or products by name, status, or date.";
                }

                // --- Catch-all / "AI" Fallback ---
                return "That's a great question! I'm specialized in <b>OMS Operations</b>. I can help you with <b>Orders</b>, <b>Stock Management</b>, <b>User Roles</b>, or <b>Dashboard Navigation</b>. <br><br>Try asking: <i>'How do I track an order?'</i> or <i>'Tell me about stock management'</i>.";
            }
        }
    }
</script>
