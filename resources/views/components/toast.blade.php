<div id="toast-container" style="position:fixed;top:18px;right:18px;z-index:9999;display:flex;flex-direction:column;gap:10px;">
    @if(session('success'))
        <div class="toast toast-success" style="display:flex;align-items:center;gap:.5rem;background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;padding:.6rem .9rem;border-radius:10px;min-width:260px;box-shadow:0 10px 30px rgba(2,6,23,.05);font-weight:600;">
            <span>✅</span><div>{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="toast toast-error" style="display:flex;align-items:center;gap:.5rem;background:#fef2f2;color:#991b1b;border:1px solid #fecaca;padding:.6rem .9rem;border-radius:10px;min-width:260px;box-shadow:0 10px 30px rgba(2,6,23,.05);font-weight:600;">
            <span>❌</span><div>{{ session('error') }}</div>
        </div>
    @endif
    @if(session('warning'))
        <div class="toast toast-warning" style="display:flex;align-items:center;gap:.5rem;background:#fffbeb;color:#92400e;border:1px solid #fde68a;padding:.6rem .9rem;border-radius:10px;min-width:260px;box-shadow:0 10px 30px rgba(2,6,23,.05);font-weight:600;">
            <span>⚠️</span><div>{{ session('warning') }}</div>
        </div>
    @endif
</div>
<script>
    (function(){
        var container = document.getElementById('toast-container');
        if (!container) return;
        var toasts = container.querySelectorAll('.toast');
        if (!toasts.length) { container.remove(); return; }
        setTimeout(function(){
            toasts.forEach(function(t){
                t.style.transition = 'opacity .4s ease, transform .4s ease';
                t.style.opacity = '0';
                t.style.transform = 'translateY(-6px)';
                setTimeout(function(){ t.remove(); if(!container.querySelector('.toast')) container.remove(); }, 400);
            });
        }, 3500);
    })();
</script>

