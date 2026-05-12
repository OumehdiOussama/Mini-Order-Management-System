<div id="chatbot-widget" class="fixed bottom-6 right-6 z-50 font-sans" dir="ltr">
    
    <!-- Chat Button -->
    <button id="chatbot-toggle" class="bg-blue-600 hover:bg-blue-700 text-white w-14 h-14 rounded-full shadow-2xl shadow-blue-500/50 flex items-center justify-center transition-all hover:scale-110 active:scale-95 focus:outline-none ring-4 ring-white">
        <svg id="chat-icon" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        <svg id="close-icon" class="w-7 h-7 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>

    <!-- Chat Window -->
    <div id="chatbot-window" class="hidden fixed bottom-24 right-6 w-[calc(100vw-3rem)] sm:w-96 bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden flex flex-col transform transition-all duration-300 origin-bottom-right z-[60] max-h-[calc(100vh-120px)] opacity-0 scale-95">
        
        <!-- Header -->
        <div class="bg-blue-600 p-4 text-white flex justify-between items-center">
            <div>
                <h3 class="font-bold text-base">OMS Support</h3>
                <p class="text-blue-100 text-[10px]">We typically reply instantly</p>
            </div>
            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse shadow-[0_0_8px_rgba(74,222,128,0.8)]"></div>
        </div>

        <!-- Messages Area -->
        <div id="chat-messages" class="p-4 flex-1 overflow-y-auto bg-slate-50 flex flex-col space-y-3">
            <!-- Bot Message -->
            <div class="flex items-start gap-2">
                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div class="bg-white border border-slate-200 p-3 rounded-2xl rounded-tl-none shadow-sm text-xs text-slate-700 leading-relaxed">
                    Hello! How can I help you today? Please choose an option below.
                </div>
            </div>
        </div>

        <!-- Actions Area (Quick Replies) -->
        <div id="chat-actions" class="p-3 bg-white border-t border-slate-100 flex flex-col gap-1.5 pb-4">
            <button onclick="handleFAQ('what')" class="text-left w-full text-xs bg-slate-50 hover:bg-blue-600 hover:text-white border border-slate-200 hover:border-blue-600 py-2 px-3 rounded-xl transition-all duration-200 font-medium hover:shadow-sm tracking-tight">What is this system?</button>
            <button onclick="handleFAQ('register')" class="text-left w-full text-xs bg-slate-50 hover:bg-blue-600 hover:text-white border border-slate-200 hover:border-blue-600 py-2 px-3 rounded-xl transition-all duration-200 font-medium hover:shadow-sm tracking-tight">How to register?</button>
            <button onclick="handleFAQ('track')" class="text-left w-full text-xs bg-slate-50 hover:bg-blue-600 hover:text-white border border-slate-200 hover:border-blue-600 py-2 px-3 rounded-xl transition-all duration-200 font-medium hover:shadow-sm tracking-tight">How to track an order?</button>
        </div>
    </div>
</div>

<script>
    const toggleBtn = document.getElementById('chatbot-toggle');
    const chatWindow = document.getElementById('chatbot-window');
    const chatIcon = document.getElementById('chat-icon');
    const closeIcon = document.getElementById('close-icon');
    const messagesArea = document.getElementById('chat-messages');

    toggleBtn.addEventListener('click', () => {
        const isHidden = chatWindow.classList.contains('hidden');
        
        if (isHidden) {
            chatWindow.classList.remove('hidden');
            // Trigger animation
            setTimeout(() => {
                chatWindow.classList.remove('scale-95', 'opacity-0');
                chatWindow.classList.add('scale-100', 'opacity-100');
            }, 10);
        } else {
            chatWindow.classList.remove('scale-100', 'opacity-100');
            chatWindow.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                chatWindow.classList.add('hidden');
            }, 200);
        }
        
        chatIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    });

    function appendUserMessage(text) {
        messagesArea.innerHTML += `
            <div class="flex items-start gap-2 justify-end mt-2">
                <div class="bg-blue-600 text-white p-3 rounded-2xl rounded-tr-none shadow-sm text-sm">
                    ${text}
                </div>
            </div>
        `;
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }

    function appendBotMessage(text, actionHtml = '') {
        // Simulating typing delay
        setTimeout(() => {
            messagesArea.innerHTML += `
                <div class="flex items-start gap-2 mt-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="bg-white border border-slate-200 p-3 rounded-2xl rounded-tl-none shadow-sm text-sm text-slate-700">
                        ${text}
                        ${actionHtml}
                    </div>
                </div>
            `;
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }, 500);
    }

    function handleFAQ(type) {
        if(type === 'what') {
            appendUserMessage("What is this system?");
            appendBotMessage("This is an Enterprise Order Management System (OMS) that helps you track your orders, manage logistics, and optimize workflows in real-time.");
        } else if(type === 'register') {
            appendUserMessage("How to register?");
            appendBotMessage("You can register by clicking the 'Get Started' button or visiting the registration page.", `<div class="mt-2"><a href="/register" class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-lg font-medium hover:bg-blue-200">Go to Register</a></div>`);
        } else if(type === 'track') {
            appendUserMessage("How to track an order?");
            appendBotMessage("Once you login, go to your Dashboard and navigate to the 'Orders' section to see the real-time status and timeline of your orders.");
        }
    }
</script>
