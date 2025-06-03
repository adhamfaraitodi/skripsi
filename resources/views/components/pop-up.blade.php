<div class="flex justify-end sm:justify-center">
    <button onclick="openModal('{{ $id }}')"
            class="text-blue-600 hover:text-blue-900 flex items-center transition-all duration-300 text-sm font-medium">
        <i class="text-base mr-1"></i>
        <span>View Detail</span>
    </button>
</div>

<div id="{{ $id }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden relative">
        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 bg-gray-50">
            <h4 class="text-lg sm:text-xl font-semibold text-gray-900">{{ $title }}</h4>
            <button onclick="closeModal('{{ $id }}')" 
                    class="p-2 hover:bg-gray-200 rounded-full transition-colors duration-200 flex-shrink-0">
                <i class="ph ph-x text-xl text-gray-600 hover:text-gray-900"></i>
            </button>
        </div>
        <div class="p-4 sm:p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
            {{ $content }}
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}
document.addEventListener('click', function(event) {
    const modals = document.querySelectorAll('[id^="order-detail"]');
    modals.forEach(modal => {
        if (event.target === modal) {
            closeModal(modal.id);
        }
    });
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = document.querySelectorAll('[id^="order-detail"]');
        modals.forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                closeModal(modal.id);
            }
        });
    }
});
</script>
<!-- animation -->
<style>
.modal-enter {
    animation: modalEnter 0.3s ease-out;
}

.modal-exit {
    animation: modalExit 0.3s ease-in;
}

@keyframes modalEnter {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes modalExit {
    from {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
    to {
        opacity: 0;
        transform: scale(0.9) translateY(-10px);
    }
}
</style>