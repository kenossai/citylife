<div>
    @if($showModal)
        @teleport('body')
            <!-- Modal Overlay -->
            <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 99999; overflow-y: auto;"
                 aria-labelledby="modal-title"
                 role="dialog"
                 aria-modal="true"
                 id="registration-modal">

                <!-- Background overlay -->
                <div wire:click="closeModal"
                     style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 99999; background-color: rgba(17, 24, 39, 0.75);"></div>

                <!-- Modal panel -->
                <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 100000; display: flex; align-items: center; justify-content: center; padding: 1rem; pointer-events: none;">
                    <div style="position: relative; max-width: 32rem; width: 100%; background: white; border-radius: 0.5rem; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1); pointer-events: auto; padding: 2rem;">

                    <!-- Close button -->
                    <div style="position: absolute; right: 0; top: 0; padding: 1rem;">
                        <button type="button"
                                wire:click="closeModal"
                                style="border-radius: 0.375rem; background: white; color: #9ca3af; border: none; cursor: pointer; padding: 0.25rem;">
                            <span style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border-width: 0;">Close</span>
                            <svg style="height: 1.5rem; width: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div style="display: flex; align-items: flex-start;">
                        <div style="margin: 0 auto; display: flex; height: 3rem; width: 3rem; flex-shrink: 0; align-items: center; justify-content: center; border-radius: 9999px; background-color: #dbeafe;">
                            <svg style="height: 1.5rem; width: 1.5rem; color: #2563eb;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <div style="margin-top: 0.75rem; text-align: center; width: 100%; margin-left: 1rem; text-align: left;">
                            <h3 style="font-size: 1.125rem; font-weight: 600; line-height: 1.75rem; color: #111827; margin: 0;" id="modal-title">
                                Join CityLife Church
                            </h3>
                            <div style="margin-top: 0.5rem;">
                                <p style="font-size: 0.875rem; line-height: 1.25rem; color: #6b7280; margin: 0;">
                                    Enter your email address to begin your registration journey with us. We'll send you a personalized registration link once approved.
                                </p>
                            </div>

                            @if($successMessage)
                                <div style="margin-top: 1rem; border-radius: 0.375rem; background-color: #f0fdf4; padding: 1rem;">
                                    <div style="display: flex;">
                                        <div style="flex-shrink: 0;">
                                            <svg style="height: 1.25rem; width: 1.25rem; color: #4ade80;" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div style="margin-left: 0.75rem;">
                                            <p style="font-size: 0.875rem; font-weight: 500; color: #166534; margin: 0;">{{ $successMessage }}</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <form wire:submit.prevent="submit" style="margin-top: 1rem;">
                                    <div>
                                        <label for="email" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">Email Address</label>
                                        <input type="email"
                                               wire:model="email"
                                               id="email"
                                               style="margin-top: 0.25rem; display: block; width: 100%; border-radius: 0.375rem; border: 1px solid #d1d5db; padding: 0.5rem 0.75rem; font-size: 0.875rem; line-height: 1.25rem; @error('email') border-color: #ef4444; @enderror"
                                               placeholder="your@email.com">
                                        @error('email')
                                            <p style="margin-top: 0.25rem; font-size: 0.875rem; color: #dc2626;">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    @if($errorMessage)
                                        <div style="margin-top: 0.75rem; border-radius: 0.375rem; background-color: #fef2f2; padding: 0.75rem;">
                                            <p style="font-size: 0.875rem; color: #991b1b; margin: 0;">{{ $errorMessage }}</p>
                                        </div>
                                    @endif

                                    <div style="margin-top: 1.25rem; display: flex; flex-direction: row-reverse; gap: 0.75rem;">
                                        <button type="submit"
                                                style="display: inline-flex; justify-content: center; border-radius: 0.375rem; background-color: #ffce1c; padding: 0.5rem 0.75rem; font-size: 0.875rem; font-weight: 600; color: white; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); border: none; cursor: pointer; white-space: nowrap;">
                                            Become a Member
                                        </button>
                                        <button type="button"
                                                wire:click="closeModal"
                                                style="display: inline-flex; justify-content: center; border-radius: 0.375rem; background-color: white; padding: 0.5rem 0.75rem; font-size: 0.875rem; font-weight: 600; color: #111827; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); border: 1px solid #d1d5db; cursor: pointer; white-space: nowrap;">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endteleport
@endif
</div>

@script
<script>
    // Listen for custom event from button
    window.addEventListener('open-registration-modal', () => {
        $wire.openModal();
    });

    // Auto-close after success
    $wire.on('interest-submitted', () => {
        setTimeout(() => {
            $wire.closeModal();
        }, 3000);
    });
</script>
@endscript
