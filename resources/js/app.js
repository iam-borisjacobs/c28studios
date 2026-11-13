import './bootstrap';
import Alpine from 'alpinejs';

// Strictly isolated to public Blade layouts (never loaded on /admin/*)
if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.start();
}

// C28 Public Engineering Control Plane UI Controllers
window.c28SwitchConsoleTab = function (tabKey) {
    const state = window.__C28_STATE__ || {};
    const tabs = state.consoleTabs || {};
    const tab = tabs[tabKey];
    if (!tab) return;

    document.querySelectorAll('.console-tab-btn').forEach((btn) => {
        if (btn.dataset.tab === tabKey) {
            btn.className = 'console-tab-btn w-full text-left px-3 py-2.5 rounded-lg text-xs font-medium flex items-center justify-between bg-emerald-500/15 text-neon-400 border border-emerald-500/30';
        } else {
            btn.className = 'console-tab-btn w-full text-left px-3 py-2.5 rounded-lg text-xs font-medium flex items-center justify-between text-slate-400 hover:text-white hover:bg-white/5 border border-transparent';
        }
    });

    const logContainer = document.getElementById('consoleTerminalLogs');
    if (logContainer && Array.isArray(tab.logs)) {
        logContainer.innerHTML = '';
        tab.logs.forEach((line) => {
            const row = document.createElement('div');
            row.className = 'text-slate-300';
            row.textContent = line;
            logContainer.appendChild(row);
        });
    }

    const outcomeEl = document.getElementById('consoleOutcomeDescription');
    if (outcomeEl && tab.outcome) {
        outcomeEl.textContent = tab.outcome;
    }
};

window.c28TriggerConsoleDeploy = function () {
    const badge = document.getElementById('consoleStatusBadge');
    if (!badge) return;
    badge.textContent = 'RUNNING CI/CD & TRIVY SECURITY SCAN...';
    setTimeout(() => {
        badge.textContent = 'DEPLOYED SHA-88C1F • 0 DOWNTIME';
    }, 800);
};

window.c28SelectArtifact = function (index) {
    const state = window.__C28_STATE__ || {};
    const artifacts = state.codeArtifacts || [];
    const item = artifacts[index];
    if (!item) return;

    const titleEl = document.getElementById('codeFileTitle');
    const codeEl = document.getElementById('codeBlockContent');
    if (titleEl) titleEl.textContent = item.title || 'artifact';
    if (codeEl) codeEl.textContent = item.code || '';

    document.querySelectorAll('.snippet-btn').forEach((btn, idx) => {
        btn.className = idx === index
            ? 'snippet-btn text-[11px] font-mono px-2.5 py-1 rounded bg-emerald-500/20 text-neon-400 border border-emerald-500/30'
            : 'snippet-btn text-[11px] font-mono px-2.5 py-1 rounded text-slate-400 hover:text-white';
    });
};

window.c28SelectBlueprintStep = function (index) {
    const state = window.__C28_STATE__ || {};
    const steps = state.blueprintSteps || [];
    const s = steps[index];
    if (!s) return;

    document.querySelectorAll('.blueprint-node-btn').forEach((btn, idx) => {
        btn.className = idx === index
            ? 'blueprint-node-btn px-3 py-2.5 rounded-xl text-left font-mono text-xs transition-all border btn-emerald text-obsidian-950 font-bold border-neon-400 shadow-lg'
            : 'blueprint-node-btn px-3 py-2.5 rounded-xl text-left font-mono text-xs transition-all border bg-obsidian-950/85 text-slate-300 border-emerald-500/20 hover:border-neon-400/50 hover:text-white';
    });

    const numEl = document.getElementById('bpStepNumber');
    const titleEl = document.getElementById('bpStepTitle');
    const delivEl = document.getElementById('bpStepDeliverable');
    const probEl = document.getElementById('bpStepProblem');
    const outEl = document.getElementById('bpStepOutcome');
    const cmdEl = document.getElementById('bpStepCommand');

    if (numEl) numEl.textContent = `STEP ${s.step || '01'} OF ${steps.length} • ${s.category || 'ENGINEERING'}`;
    if (titleEl) titleEl.textContent = s.title || '';
    if (delivEl) delivEl.textContent = s.deliverable || '';
    if (probEl) probEl.textContent = s.problem || '';
    if (outEl) outEl.textContent = `✓ ${s.outcome || ''}`;
    if (cmdEl) cmdEl.textContent = s.cmd || '';
};

window.c28SetPricingMode = function (mode) {
    const sprintGrid = document.getElementById('pricingGridSprint');
    const retainerGrid = document.getElementById('pricingGridRetainer');
    const btnSprint = document.getElementById('btnModeSprint');
    const btnRetainer = document.getElementById('btnModeRetainer');

    if (mode === 'retainer') {
        if (sprintGrid) sprintGrid.classList.add('hidden');
        if (retainerGrid) retainerGrid.classList.remove('hidden');
        if (btnRetainer) btnRetainer.className = 'px-5 py-2 rounded-full text-xs font-bold transition-all btn-emerald text-obsidian-950';
        if (btnSprint) btnSprint.className = 'px-5 py-2 rounded-full text-xs font-semibold text-slate-400 hover:text-white transition-all';
    } else {
        if (sprintGrid) sprintGrid.classList.remove('hidden');
        if (retainerGrid) retainerGrid.classList.add('hidden');
        if (btnSprint) btnSprint.className = 'px-5 py-2 rounded-full text-xs font-bold transition-all btn-emerald text-obsidian-950';
        if (btnRetainer) btnRetainer.className = 'px-5 py-2 rounded-full text-xs font-semibold text-slate-400 hover:text-white transition-all';
    }
};

window.c28ToggleFaq = function (index) {
    document.querySelectorAll('[data-faq-body]').forEach((body) => {
        const idx = Number(body.getAttribute('data-faq-body'));
        const icon = document.querySelector(`[data-faq-icon="${idx}"]`);
        if (idx === index) {
            const isHidden = body.classList.contains('hidden');
            body.classList.toggle('hidden');
            if (icon) icon.textContent = isHidden ? '−' : '+';
        } else {
            body.classList.add('hidden');
            if (icon) icon.textContent = '+';
        }
    });
};

window.c28CopyCode = function (btn) {
    const code = btn.getAttribute('data-code') || '';
    if (navigator.clipboard && code) {
        navigator.clipboard.writeText(code);
    }
    const prev = btn.textContent;
    btn.textContent = 'Copied ✓';
    setTimeout(() => {
        btn.textContent = prev;
    }, 1200);
};

window.c28OpenModal = function (contextLabel, engagementMode = 'sprint_fixed') {
    const modal = document.getElementById('consultationModal');
    const badge = document.getElementById('modalBadge');
    const form = document.getElementById('modalForm');
    const success = document.getElementById('modalSuccess');
    const errors = document.getElementById('modalErrors');
    const serviceSelect = document.getElementById('modalServiceSelect');
    const modeSelect = document.getElementById('modalEngagementMode');

    if (!modal) return;

    if (badge && contextLabel) {
        badge.textContent = `C28 • ${String(contextLabel).toUpperCase()}`;
    }
    if (serviceSelect && contextLabel) {
        const normalized = String(contextLabel).toLowerCase();
        if (normalized.includes('devsecops') || normalized.includes('security')) {
            serviceSelect.value = 'DevSecOps Integration';
        } else if (normalized.includes('software') || normalized.includes('saas')) {
            serviceSelect.value = 'Software Development';
        } else if (normalized.includes('website') || normalized.includes('web')) {
            serviceSelect.value = 'Website Development';
        } else {
            serviceSelect.value = 'DevOps & Cloud Engineering';
        }
    }
    if (modeSelect && engagementMode) {
        modeSelect.value = engagementMode;
    }
    if (errors) {
        errors.classList.add('hidden');
        errors.innerHTML = '';
    }
    if (form) form.classList.remove('hidden');
    if (success) success.classList.add('hidden');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
};

window.c28CloseModal = function () {
    const modal = document.getElementById('consultationModal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
};

window.c28SubmitModal = async function (event) {
    event.preventDefault();
    const form = document.getElementById('modalForm');
    const success = document.getElementById('modalSuccess');
    const errorsBox = document.getElementById('modalErrors');
    const submitBtn = document.getElementById('modalSubmitBtn');
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');

    if (!form) return;

    if (errorsBox) {
        errorsBox.classList.add('hidden');
        errorsBox.innerHTML = '';
    }

    const payload = {
        name: document.getElementById('modalNameInput')?.value || '',
        email: document.getElementById('modalEmailInput')?.value || '',
        company: document.getElementById('modalCompanyInput')?.value || '',
        engagement_mode: document.getElementById('modalEngagementMode')?.value || 'sprint_fixed',
        service_interest: document.getElementById('modalServiceSelect')?.value || 'DevOps & Cloud Engineering',
        message: document.getElementById('modalNotes')?.value || '',
        website_url: document.getElementById('modalWebsiteUrlInput')?.value || '',
    };

    const originalLabel = submitBtn ? submitBtn.textContent : 'Send Engineering Request';
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Logging Inquiry...';
    }

    try {
        const response = await fetch(form.getAttribute('action') || '/inquiries', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfMeta ? csrfMeta.getAttribute('content') : '',
            },
            body: JSON.stringify(payload),
        });

        const data = await response.json().catch(() => ({}));

        if (response.ok) {
            form.reset();
            form.classList.add('hidden');
            if (success) success.classList.remove('hidden');
        } else if (errorsBox) {
            const messages = [];
            if (data.errors && typeof data.errors === 'object') {
                Object.values(data.errors).forEach((arr) => {
                    if (Array.isArray(arr)) messages.push(...arr);
                });
            } else if (data.message) {
                messages.push(data.message);
            } else {
                messages.push('Unable to submit inquiry. Please check your inputs and try again.');
            }
            errorsBox.innerHTML = messages.map((m) => `<div>&bull; ${m}</div>`).join('');
            errorsBox.classList.remove('hidden');
        }
    } catch {
        if (errorsBox) {
            errorsBox.innerHTML = '<div>&bull; Network error occurred. Please try again or email engineering@c28studios.com.</div>';
            errorsBox.classList.remove('hidden');
        }
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = originalLabel;
        }
    }
};

window.c28ToggleMobileNav = function () {
    const nav = document.getElementById('c28MobileNav');
    if (nav) nav.classList.toggle('hidden');
};

window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        window.c28CloseModal();
    }
});
