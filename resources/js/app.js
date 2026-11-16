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
    const copyBtn = document.getElementById("codeCopyBtn");
    if (titleEl) titleEl.textContent = item.title || "artifact";
    if (codeEl) codeEl.textContent = item.code || "";
    if (copyBtn) copyBtn.setAttribute("data-code", item.code || "");

    document.querySelectorAll(".snippet-btn").forEach((btn, idx) => {
        btn.className =
            idx === index
                ? "snippet-btn whitespace-nowrap shrink-0 text-[11px] font-mono px-2.5 sm:px-3 py-1.5 rounded-lg transition bg-emerald-500/20 text-neon-400 border border-emerald-500/30 font-semibold"
                : "snippet-btn whitespace-nowrap shrink-0 text-[11px] font-mono px-2.5 sm:px-3 py-1.5 rounded-lg transition text-slate-400 hover:text-white border border-transparent";
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
    if (window.c28SyncCustomSelects) {
        window.c28SyncCustomSelects();
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
    if (window.c28CloseAllDropdowns) {
        window.c28CloseAllDropdowns();
    }
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
            if (window.c28SyncCustomSelects) {
                window.c28SyncCustomSelects();
            }
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
            errorsBox.textContent = "";
            messages.forEach((m) => {
                const row = document.createElement("div");
                row.textContent = `• ${String(m)}`;
                errorsBox.appendChild(row);
            });
            errorsBox.classList.remove("hidden");
        }
    } catch {
        if (errorsBox) {
            errorsBox.textContent = "";
            const row = document.createElement("div");
            row.textContent =
                "• Network error occurred. Please try again or email contact@c28studios.dev.";
            errorsBox.appendChild(row);
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

// Theme Switcher (System Theme Auto-Detection + Manual Toggle)
window.c28ApplyThemeMode = function (mode, persistOverride = false) {
    const root = document.documentElement;
    const nextMode = mode === "light" ? "light" : "dark";

    if (nextMode === "light") {
        root.classList.add("theme-light");
        root.classList.remove("dark");
    } else {
        root.classList.add("dark");
        root.classList.remove("theme-light");
    }

    if (persistOverride) {
        try {
            sessionStorage.setItem("c28_theme_override", nextMode);
        } catch {}
    }

    window.c28UpdateThemeButton();
    if (window.c28SwitchHeroAnimationTheme) {
        window.c28SwitchHeroAnimationTheme(nextMode);
    }
};

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
    window.c28ApplyThemeMode(nextMode, true);
};

// Bottom-to-Top ("Back to Top") Controller
window.c28ScrollToTop = function () {
    window.scrollTo({ top: 0, behavior: "smooth" });
};

(function initThemeAndBackToTop() {
    window.c28UpdateThemeButton();

    // Listen for live OS / browser system color scheme changes
    if (window.matchMedia) {
        const mediaQuery = window.matchMedia("(prefers-color-scheme: light)");
        const handleSystemThemeChange = (e) => {
            try {
                sessionStorage.removeItem("c28_theme_override");
            } catch {}
            window.c28ApplyThemeMode(e.matches ? "light" : "dark", false);
        };

        if (typeof mediaQuery.addEventListener === "function") {
            mediaQuery.addEventListener("change", handleSystemThemeChange);
        } else if (typeof mediaQuery.addListener === "function") {
            mediaQuery.addListener(handleSystemThemeChange);
        }
    }

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

// Custom C28 Select Dropdown Enhancer
(function initCustomSelects() {
    const selects = Array.from(document.querySelectorAll("select.c28-select"));
    if (!selects.length) return;

    const instances = [];

    function closeAll(exceptWrapper) {
        instances.forEach((inst) => {
            if (inst.wrapper !== exceptWrapper) {
                inst.wrapper.classList.remove("is-open");
                inst.menu.classList.add("hidden");
                inst.trigger.setAttribute("aria-expanded", "false");
            }
        });
    }

    window.c28CloseAllDropdowns = function () {
        closeAll(null);
    };

    window.c28SyncCustomSelects = function () {
        instances.forEach((inst) => inst.syncFromSelect());
    };

    selects.forEach((select) => {
        if (select.dataset.c28Enhanced === "true") return;
        select.dataset.c28Enhanced = "true";

        const wrapper = document.createElement("div");
        wrapper.className = "c28-dropdown";

        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);
        select.classList.add("sr-only");
        select.setAttribute("tabindex", "-1");

        const trigger = document.createElement("button");
        trigger.type = "button";
        trigger.className = "c28-dropdown-trigger text-xs sm:text-sm";
        trigger.setAttribute("aria-haspopup", "listbox");
        trigger.setAttribute("aria-expanded", "false");

        const labelSpan = document.createElement("span");
        labelSpan.className = "truncate font-medium";

        const chevron = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        chevron.setAttribute("viewBox", "0 0 24 24");
        chevron.setAttribute("fill", "none");
        chevron.setAttribute("stroke", "currentColor");
        chevron.setAttribute("stroke-width", "2.25");
        chevron.setAttribute("class", "c28-dropdown-chevron");
        chevron.innerHTML =
            '<path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>';

        trigger.appendChild(labelSpan);
        trigger.appendChild(chevron);

        const menu = document.createElement("div");
        menu.className = "c28-dropdown-menu hidden";
        menu.setAttribute("role", "listbox");

        const optionButtons = [];

        Array.from(select.options).forEach((opt) => {
            const itemBtn = document.createElement("button");
            itemBtn.type = "button";
            itemBtn.className = "c28-dropdown-option";
            itemBtn.setAttribute("role", "option");
            itemBtn.dataset.value = opt.value;

            const textWrap = document.createElement("div");
            textWrap.className = "min-w-0 flex-1";

            const titleDiv = document.createElement("div");
            titleDiv.className = "c28-dropdown-option-title";
            titleDiv.textContent = opt.textContent.trim();
            textWrap.appendChild(titleDiv);

            const subtitle = opt.dataset.subtitle;
            if (subtitle) {
                const subDiv = document.createElement("div");
                subDiv.className = "c28-dropdown-option-subtitle";
                subDiv.textContent = subtitle;
                textWrap.appendChild(subDiv);
            }

            const checkSpan = document.createElement("span");
            checkSpan.className = "c28-dropdown-check";
            checkSpan.innerHTML = "&#10003;";

            itemBtn.appendChild(textWrap);
            itemBtn.appendChild(checkSpan);

            itemBtn.addEventListener("click", () => {
                select.value = opt.value;
                select.dispatchEvent(new Event("change", { bubbles: true }));
                syncFromSelect();
                closeAll(null);
                trigger.focus();
            });

            optionButtons.push(itemBtn);
            menu.appendChild(itemBtn);
        });

        function syncFromSelect() {
            const activeOpt =
                select.options[select.selectedIndex] || select.options[0];
            labelSpan.textContent = activeOpt
                ? activeOpt.textContent.trim()
                : "";
            optionButtons.forEach((btn) => {
                const selected = activeOpt && btn.dataset.value === activeOpt.value;
                btn.classList.toggle("is-selected", Boolean(selected));
                btn.setAttribute("aria-selected", selected ? "true" : "false");
            });
        }

        trigger.addEventListener("click", (e) => {
            e.stopPropagation();
            const isOpen = wrapper.classList.contains("is-open");
            closeAll(wrapper);
            if (isOpen) {
                wrapper.classList.remove("is-open");
                menu.classList.add("hidden");
                trigger.setAttribute("aria-expanded", "false");
            } else {
                wrapper.classList.add("is-open");
                menu.classList.remove("hidden");
                trigger.setAttribute("aria-expanded", "true");
            }
        });

        select.addEventListener("change", syncFromSelect);

        wrapper.appendChild(trigger);
        wrapper.appendChild(menu);

        syncFromSelect();
        instances.push({ wrapper, trigger, menu, syncFromSelect });
    });

    document.addEventListener("click", (e) => {
        if (!e.target.closest(".c28-dropdown")) {
            closeAll(null);
        }
    });

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeAll(null);
        }
    });
})();

