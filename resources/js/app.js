import "./bootstrap";
import Alpine from "alpinejs";

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

    document.querySelectorAll(".console-tab-btn").forEach((btn) => {
        if (btn.dataset.tab === tabKey) {
            btn.className =
                "console-tab-btn w-full text-left px-3 py-2.5 rounded-lg text-xs font-medium flex items-center justify-between bg-emerald-500/15 text-neon-400 border border-emerald-500/30";
        } else {
            btn.className =
                "console-tab-btn w-full text-left px-3 py-2.5 rounded-lg text-xs font-medium flex items-center justify-between text-slate-400 hover:text-white hover:bg-white/5 border border-transparent";
        }
    });

    const logContainer = document.getElementById("consoleTerminalLogs");
    if (logContainer && Array.isArray(tab.logs)) {
        logContainer.innerHTML = "";
        tab.logs.forEach((line) => {
            const row = document.createElement("div");
            row.className = "text-slate-300";
            row.textContent = line;
            logContainer.appendChild(row);
        });
    }

    const outcomeEl = document.getElementById("consoleOutcomeDescription");
    if (outcomeEl && tab.outcome) {
        outcomeEl.textContent = tab.outcome;
    }
};

window.c28TriggerConsoleDeploy = function () {
    const badge = document.getElementById("consoleStatusBadge");
    if (!badge) return;
    badge.textContent = "RUNNING CI/CD & TRIVY SECURITY SCAN...";
    setTimeout(() => {
        badge.textContent = "DEPLOYED SHA-88C1F • 0 DOWNTIME";
    }, 800);
};

window.c28SelectArtifact = function (index) {
    const state = window.__C28_STATE__ || {};
    const artifacts = state.codeArtifacts || [];
    const item = artifacts[index];
    if (!item) return;

    const titleEl = document.getElementById("codeFileTitle");
    const codeEl = document.getElementById("codeBlockContent");
    if (titleEl) titleEl.textContent = item.title || "artifact";
    if (codeEl) codeEl.textContent = item.code || "";

    document.querySelectorAll(".snippet-btn").forEach((btn, idx) => {
        btn.className =
            idx === index
                ? "snippet-btn text-[11px] font-mono px-2.5 py-1 rounded bg-emerald-500/20 text-neon-400 border border-emerald-500/30"
                : "snippet-btn text-[11px] font-mono px-2.5 py-1 rounded text-slate-400 hover:text-white";
    });
};

window.c28SelectBlueprintStep = function (index) {
    const state = window.__C28_STATE__ || {};
    const steps = state.blueprintSteps || [];
    const s = steps[index];
    if (!s) return;

    document.querySelectorAll(".blueprint-node-btn").forEach((btn, idx) => {
        btn.className =
            idx === index
                ? "blueprint-node-btn px-4 py-3.5 rounded-xl text-left font-mono text-xs transition-all border btn-emerald text-obsidian-950 font-bold border-neon-400 shadow-lg"
                : "blueprint-node-btn px-4 py-3.5 rounded-xl text-left font-mono text-xs transition-all border bg-obsidian-950/85 text-slate-300 border-emerald-500/20 hover:border-neon-400/50 hover:text-white";
    });

    const numEl = document.getElementById("bpStepNumber");
    const titleEl = document.getElementById("bpStepTitle");
    const delivEl = document.getElementById("bpStepDeliverable");
    const probEl = document.getElementById("bpStepProblem");
    const outEl = document.getElementById("bpStepOutcome");
    const cmdEl = document.getElementById("bpStepCommand");

    if (numEl)
        numEl.textContent = `STEP ${s.step || "01"} OF ${steps.length} • ${s.category || "ENGINEERING"}`;
    if (titleEl) titleEl.textContent = s.title || "";
    if (delivEl) delivEl.textContent = s.deliverable || "";
    if (probEl) probEl.textContent = s.problem || "";
    if (outEl) outEl.textContent = `✓ ${s.outcome || ""}`;
    if (cmdEl) cmdEl.textContent = s.cmd || "";
};

window.c28SetPricingMode = function (mode) {
    const sprintGrid = document.getElementById("pricingGridSprint");
    const retainerGrid = document.getElementById("pricingGridRetainer");
    const btnSprint = document.getElementById("btnModeSprint");
    const btnRetainer = document.getElementById("btnModeRetainer");

    if (mode === "retainer") {
        if (sprintGrid) sprintGrid.classList.add("hidden");
        if (retainerGrid) retainerGrid.classList.remove("hidden");
        if (btnRetainer)
            btnRetainer.className =
                "px-5 py-2 rounded-full text-xs font-bold transition-all btn-emerald text-obsidian-950";
        if (btnSprint)
            btnSprint.className =
                "px-5 py-2 rounded-full text-xs font-semibold text-slate-400 hover:text-white transition-all";
    } else {
        if (sprintGrid) sprintGrid.classList.remove("hidden");
        if (retainerGrid) retainerGrid.classList.add("hidden");
        if (btnSprint)
            btnSprint.className =
                "px-5 py-2 rounded-full text-xs font-bold transition-all btn-emerald text-obsidian-950";
        if (btnRetainer)
            btnRetainer.className =
                "px-5 py-2 rounded-full text-xs font-semibold text-slate-400 hover:text-white transition-all";
    }
};

window.c28ToggleFaq = function (index) {
    document.querySelectorAll("[data-faq-body]").forEach((body) => {
        const idx = Number(body.getAttribute("data-faq-body"));
        const icon = document.querySelector(`[data-faq-icon="${idx}"]`);
        if (idx === index) {
            const isHidden = body.classList.contains("hidden");
            body.classList.toggle("hidden");
            if (icon) icon.textContent = isHidden ? "−" : "+";
        } else {
            body.classList.add("hidden");
            if (icon) icon.textContent = "+";
        }
    });
};

window.c28CopyCode = function (btn) {
    const code = btn.getAttribute("data-code") || "";
    if (navigator.clipboard && code) {
        navigator.clipboard.writeText(code);
    }
    const prev = btn.textContent;
    btn.textContent = "Copied ✓";
    setTimeout(() => {
        btn.textContent = prev;
    }, 1200);
};

window.c28OpenModal = function (contextLabel, engagementMode = "sprint_fixed") {
    const modal = document.getElementById("consultationModal");
    const badge = document.getElementById("modalBadge");
    const form = document.getElementById("modalForm");
    const success = document.getElementById("modalSuccess");
    const errors = document.getElementById("modalErrors");
    const serviceSelect = document.getElementById("modalServiceSelect");
    const modeSelect = document.getElementById("modalEngagementMode");

    if (!modal) return;

    if (badge && contextLabel) {
        badge.textContent = `C28 • ${String(contextLabel).toUpperCase()}`;
    }
    if (serviceSelect && contextLabel) {
        const normalized = String(contextLabel).toLowerCase();
        if (
            normalized.includes("devsecops") ||
            normalized.includes("security")
        ) {
            serviceSelect.value = "DevSecOps Integration";
        } else if (
            normalized.includes("software") ||
            normalized.includes("saas")
        ) {
            serviceSelect.value = "Software Development";
        } else if (
            normalized.includes("website") ||
            normalized.includes("web")
        ) {
            serviceSelect.value = "Website Development";
        } else {
            serviceSelect.value = "DevOps & Cloud Engineering";
        }
    }
    if (modeSelect && engagementMode) {
        modeSelect.value = engagementMode;
    }
    if (errors) {
        errors.classList.add("hidden");
        errors.innerHTML = "";
    }
    if (form) form.classList.remove("hidden");
    if (success) success.classList.add("hidden");
    modal.classList.remove("hidden");
    modal.classList.add("flex");
};

window.c28CloseModal = function () {
    const modal = document.getElementById("consultationModal");
    if (!modal) return;
    modal.classList.add("hidden");
    modal.classList.remove("flex");
};

window.c28SubmitModal = async function (event) {
    event.preventDefault();
    const form = document.getElementById("modalForm");
    const success = document.getElementById("modalSuccess");
    const errorsBox = document.getElementById("modalErrors");
    const submitBtn = document.getElementById("modalSubmitBtn");
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');

    if (!form) return;

    if (errorsBox) {
        errorsBox.classList.add("hidden");
        errorsBox.innerHTML = "";
    }

    const payload = {
        name: document.getElementById("modalNameInput")?.value || "",
        email: document.getElementById("modalEmailInput")?.value || "",
        company: document.getElementById("modalCompanyInput")?.value || "",
        engagement_mode:
            document.getElementById("modalEngagementMode")?.value ||
            "sprint_fixed",
        service_interest:
            document.getElementById("modalServiceSelect")?.value ||
            "DevOps & Cloud Engineering",
        message: document.getElementById("modalNotes")?.value || "",
        website_url:
            document.getElementById("modalWebsiteUrlInput")?.value || "",
    };

    const originalLabel = submitBtn
        ? submitBtn.textContent
        : "Send Engineering Request";
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = "Logging Inquiry...";
    }

    try {
        const response = await fetch(
            form.getAttribute("action") || "/inquiries",
            {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfMeta
                        ? csrfMeta.getAttribute("content")
                        : "",
                },
                body: JSON.stringify(payload),
            },
        );

        const data = await response.json().catch(() => ({}));

        if (response.ok) {
            form.reset();
            form.classList.add("hidden");
            if (success) success.classList.remove("hidden");
        } else if (errorsBox) {
            const messages = [];
            if (data.errors && typeof data.errors === "object") {
                Object.values(data.errors).forEach((arr) => {
                    if (Array.isArray(arr)) messages.push(...arr);
                });
            } else if (data.message) {
                messages.push(data.message);
            } else {
                messages.push(
                    "Unable to submit inquiry. Please check your inputs and try again.",
                );
            }
            errorsBox.innerHTML = messages
                .map((m) => `<div>&bull; ${m}</div>`)
                .join("");
            errorsBox.classList.remove("hidden");
        }
    } catch {
        if (errorsBox) {
            errorsBox.innerHTML =
                "<div>&bull; Network error occurred. Please try again or email engineering@c28studios.com.</div>";
            errorsBox.classList.remove("hidden");
        }
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = originalLabel;
        }
    }
};

window.c28ToggleMobileNav = function () {
    const nav = document.getElementById("c28MobileNav");
    if (nav) nav.classList.toggle("hidden");
};

// Theme Switcher (Dark #181818 <-> White/Light Theme)
window.c28UpdateThemeButton = function () {
    const labelEl = document.getElementById("c28ThemeToggleLabel");
    if (!labelEl) return;
    const isLight = document.documentElement.classList.contains("theme-light");
    labelEl.textContent = isLight ? "☀ White" : "☾ Dark";
};

window.c28ToggleTheme = function () {
    const root = document.documentElement;
    const isLight = root.classList.contains("theme-light");
    const nextMode = isLight ? "dark" : "light";

    if (nextMode === "light") {
        root.classList.add("theme-light");
        root.classList.remove("dark");
    } else {
        root.classList.add("dark");
        root.classList.remove("theme-light");
    }

    try {
        localStorage.setItem("c28_theme", nextMode);
    } catch {}

    window.c28UpdateThemeButton();
    if (window.c28SwitchHeroAnimationTheme) {
        window.c28SwitchHeroAnimationTheme(nextMode);
    }
};

// Bottom-to-Top ("Back to Top") Controller
window.c28ScrollToTop = function () {
    window.scrollTo({ top: 0, behavior: "smooth" });
};

(function initThemeAndBackToTop() {
    window.c28UpdateThemeButton();

    const backToTopBtn = document.getElementById("c28BackToTop");
    if (!backToTopBtn) return;

    const onScroll = () => {
        if (window.scrollY > 320) {
            backToTopBtn.classList.remove("hidden");
            backToTopBtn.classList.add("flex");
        } else {
            backToTopBtn.classList.add("hidden");
            backToTopBtn.classList.remove("flex");
        }
    };

    window.addEventListener("scroll", onScroll, { passive: true });
    onScroll();
})();

window.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
        window.c28CloseModal();
    }
});

// Hero EZGIF Frame Sequence Player (Supports Dark & White/Light Theme Sequences)
(function initHeroEzgifPlayer() {
    const canvas = document.getElementById("heroEzgifCanvas");
    const fallback = document.getElementById("heroEzgifFallback");
    if (!canvas) return;

    const darkBaseUrl = canvas.dataset.baseUrlDark || canvas.dataset.baseUrl;
    const lightBaseUrl = canvas.dataset.baseUrlLight || darkBaseUrl;
    const totalFrames = parseInt(canvas.dataset.frameCount || "235", 10);
    if (!darkBaseUrl || !totalFrames) return;

    const ctx = canvas.getContext("2d");
    if (!ctx) return;

    const frameSets = {
        dark: new Array(totalFrames),
        light: new Array(totalFrames),
    };
    const loadedSets = {
        dark: false,
        light: false,
    };

    let activeTheme = document.documentElement.classList.contains("theme-light")
        ? "light"
        : "dark";
    let started = false;
    let currentFrame = 0;
    let lastTime = 0;
    const frameInterval = 1000 / 30; // 30 fps

    function frameSrc(baseUrl, index) {
        const num = String(index + 1).padStart(3, "0");
        return `${baseUrl}/ezgif-frame-${num}.jpg`;
    }

    function drawCroppedFrame(img) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.globalCompositeOperation = "source-over";
        ctx.drawImage(
            img,
            10,
            10,
            img.naturalWidth - 20,
            img.naturalHeight - 20,
            0,
            0,
            canvas.width,
            canvas.height,
        );
        if (activeTheme === "light") {
            // Counter-balance the top-to-bottom brightness gradient in the source light frames
            // so the entire canvas background normalizes to uniform #D1DACF from top to bottom
            const grad = ctx.createLinearGradient(0, 0, 0, canvas.height);
            grad.addColorStop(0.0, "#e7ece6");
            grad.addColorStop(0.25, "#e5eae4");
            grad.addColorStop(0.5, "#dee3dd");
            grad.addColorStop(0.75, "#d6dcd5");
            grad.addColorStop(1.0, "#d5dbd4");
            ctx.globalCompositeOperation = "multiply";
            ctx.fillStyle = grad;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.globalCompositeOperation = "source-over";
        }
    }

    function tick(timestamp) {
        if (!lastTime) lastTime = timestamp;
        const elapsed = timestamp - lastTime;

        if (elapsed >= frameInterval) {
            lastTime = timestamp - (elapsed % frameInterval);
            const currentSet = frameSets[activeTheme] || frameSets.dark;
            const img = currentSet[currentFrame];
            if (img && img.complete && img.naturalWidth > 0) {
                drawCroppedFrame(img);
                currentFrame = (currentFrame + 1) % totalFrames;
            }
        }
        window.requestAnimationFrame(tick);
    }

    function startPlayback() {
        if (started) return;
        started = true;
        canvas.classList.remove("hidden");
        canvas.classList.add("block");
        if (fallback) fallback.classList.add("hidden");
        window.requestAnimationFrame(tick);
    }

    function ensureThemeFramesLoaded(mode) {
        const targetMode = mode === "light" ? "light" : "dark";
        if (loadedSets[targetMode]) return;
        loadedSets[targetMode] = true;

        const baseUrl = targetMode === "light" ? lightBaseUrl : darkBaseUrl;
        const set = frameSets[targetMode];

        for (let i = 0; i < totalFrames; i++) {
            const img = new Image();
            img.decoding = "async";
            if (i === 0) {
                img.onload = () => {
                    if (activeTheme === targetMode) {
                        drawCroppedFrame(img);
                        startPlayback();
                    }
                };
            }
            img.src = frameSrc(baseUrl, i);
            set[i] = img;
        }
    }

    window.c28SwitchHeroAnimationTheme = function (mode) {
        activeTheme = mode === "light" ? "light" : "dark";
        if (fallback) {
            const fallbackSrc =
                activeTheme === "light"
                    ? fallback.dataset.srcLight
                    : fallback.dataset.srcDark;
            if (fallbackSrc) fallback.src = fallbackSrc;
        }
        ensureThemeFramesLoaded(activeTheme);
        const set = frameSets[activeTheme];
        const immediateImg = set[currentFrame] || set[0];
        if (immediateImg && immediateImg.complete && immediateImg.naturalWidth > 0) {
            drawCroppedFrame(immediateImg);
        }
    };

    // Initialize fallback image and preload active theme sequence immediately
    if (fallback) {
        const initialFallback =
            activeTheme === "light"
                ? fallback.dataset.srcLight
                : fallback.dataset.srcDark;
        if (initialFallback) fallback.src = initialFallback;
    }
    ensureThemeFramesLoaded(activeTheme);
})();
