<div class="grid grid-cols-1 md:grid-cols-4 gap-4" wire:poll.5s="loadThreads">
    <div class="md:col-span-1 bg-gray-900 rounded-lg shadow p-4">
        <h3 class="font-semibold mb-3 text-gray-100">Threads</h3>
        <div class="space-y-2 max-h-96 overflow-y-auto">
            <?php $__empty_1 = true; $__currentLoopData = $threads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <button type="button" wire:click="selectThread(<?php echo e($t['user_id']); ?>)"
                        class="w-full text-left px-3 py-2 rounded <?php echo e((int)$selectedUserId === (int)$t['user_id'] ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-gray-100'); ?>">
                    <div class="font-medium"><?php echo e($t['name']); ?></div>
                    <div class="text-xs opacity-70">#<?php echo e($t['user_id']); ?> · <?php echo e($t['last_at']); ?></div>
                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-gray-400">No threads yet.</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="md:col-span-3 chat-box rounded-lg shadow p-4 flex flex-col"
         x-data="{ scroll(){ this.$nextTick(() => { const el = $refs.list; if (el) { el.scrollTop = el.scrollHeight } }) }, init(){ this.scroll(); this.$watch('$wire.messages', () => this.scroll()); } }">
        <h3 class="font-semibold mb-3 text-heading">Conversation</h3>

        <div class="flex-1 overflow-y-auto space-y-2 pr-2 relative" wire:poll.5s="loadMessages" x-ref="list" @scroll="atBottom = ($refs.list.scrollTop + $refs.list.clientHeight) >= ($refs.list.scrollHeight - 4)">
            <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex justify-start transition-opacity duration-200 ease-out" x-data x-init="$el.classList.add('opacity-0','translate-y-1'); setTimeout(()=>{$el.classList.remove('opacity-0','translate-y-1')}, 0)">
                    <div class="chat-bubble px-3 py-2 rounded-lg <?php echo e(($m['direction'] ?? '') === 'inbound' ? 'chat-bubble-in' : 'chat-bubble-out'); ?>">
                        <div class="flex items-baseline justify-between gap-2 mb-1">
                            <div class="chat-meta-left">
                                <?php echo e(($m['direction'] ?? '') === 'inbound' ? 'Admin' : (($m['user_name'] ?? null) ?: ('User #' . ($m['user_id'] ?? '')))); ?>

                            </div>
                            <div class="chat-meta-right"><?php echo e($m['created_at'] ?? ''); ?></div>
                        </div>
                        <div><?php echo e($m['message'] ?? ''); ?></div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="chat-empty">No messages in this thread.</div>
            <?php endif; ?>

            <button x-show="!atBottom" @click="scroll()" class="hidden md:flex items-center gap-1 px-2 py-1 rounded-full bg-indigo-600 text-white text-xs shadow absolute bottom-2 right-2" x-transition.opacity>
                New
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M2.25 12a.75.75 0 0 1 .75-.75h15.19l-5.22-5.22a.75.75 0 1 1 1.06-1.06l6.5 6.5a.75.75 0 0 1 0 1.06l-6.5 6.5a.75.75 0 1 1-1.06-1.06l5.22-5.22H3a.75.75 0 0 1-.75-.75z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <form wire:submit.prevent="send" class="mt-3 flex gap-2 items-center" @submit.prevent="scroll()">
            <input type="text" wire:model.defer="reply" class="chat-input flex-1 rounded-md bg-white text-black border border-gray-300 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 focus:text-black" placeholder="Write a reply...">
            <button type="submit" aria-label="Send" class="p-2 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white shadow">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd" d="M2.25 12a.75.75 0 0 1 .75-.75h15.19l-5.22-5.22a.75.75 0 1 1 1.06-1.06l6.5 6.5a.75.75 0 0 1 0 1.06l-6.5 6.5a.75.75 0 1 1-1.06-1.06l5.22-5.22H3a.75.75 0 0 1-.75-.75z" clip-rule="evenodd" />
                </svg>
            </button>
        </form>
    </div>
</div>
<?php /**PATH /Users/admin/Desktop/project/dash_3/resources/views/livewire/admin/support-chat.blade.php ENDPATH**/ ?>