<!-- KulaAI Assistant Floating Trigger & Drawer Modal -->
<div id="kula-ai-container">
    <style>
        /* Floating Trigger Button */
        #kula-ai-trigger-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 99999;
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            color: #ffffff;
            border: none;
            border-radius: 30px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(4, 120, 87, 0.4);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #kula-ai-trigger-btn:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 14px 30px rgba(5, 150, 105, 0.5);
        }

        /* Slide-Out Drawer Overlay */
        #kula-ai-drawer-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 999998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        #kula-ai-drawer-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Slide-Out Drawer Panel */
        #kula-ai-drawer {
            position: fixed;
            top: 0;
            right: -800px;
            width: 460px;
            max-width: 95vw;
            height: 100vh;
            background: #0f172a;
            color: #f8fafc;
            z-index: 999999;
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            transition: width 0.35s cubic-bezier(0.16, 1, 0.3, 1), right 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            font-family: inherit;
        }
        #kula-ai-drawer.active {
            right: 0;
        }
        #kula-ai-drawer.history-open {
            width: 740px;
        }

        .kula-drawer-header {
            padding: 18px 20px;
            background: #1e293b;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }
        .kula-drawer-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
        }
        .kula-drawer-close {
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 24px;
            cursor: pointer;
            line-height: 1;
            padding: 4px;
        }
        .kula-drawer-close:hover { color: #ffffff; }

        /* Flex Layout for Drawer Main Body */
        .kula-drawer-content-body {
            flex: 1;
            display: flex;
            height: calc(100% - 61px);
            overflow: hidden;
            position: relative;
        }

        /* History Sidebar - Positioned Side-by-Side */
        #kula-chat-history-panel {
            width: 260px;
            height: 100%;
            background: #090d16;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            display: none;
            flex-direction: column;
            flex-shrink: 0;
        }
        #kula-ai-drawer.history-open #kula-chat-history-panel {
            display: flex;
        }

        /* Main Chat Wrapper - Takes Remaining Flex Space */
        .kula-chat-main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
        }

        .kula-drawer-body {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .kula-quick-prompts {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }
        .kula-chip {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #cbd5e1;
            padding: 6px 12px;
            border-radius: 16px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .kula-chip:hover {
            background: rgba(99, 102, 241, 0.2);
            border-color: #6366f1;
            color: #ffffff;
        }

        .kula-msg {
            display: flex;
            flex-direction: column;
            max-width: 88%;
            border-radius: 14px;
            padding: 12px 16px;
            font-size: 13.5px;
            line-height: 1.5;
        }
        .kula-msg-user {
            align-self: flex-end;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            border-bottom-right-radius: 4px;
        }
        .kula-msg-ai {
            align-self: flex-start;
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
            border-bottom-left-radius: 4px;
        }
        .kula-msg-ai p { margin: 0 0 8px 0; }
        .kula-msg-ai p:last-child { margin-bottom: 0; }
        .kula-msg-ai ul { margin: 4px 0; padding-left: 20px; }

        .kula-drawer-footer {
            padding: 16px;
            background: #1e293b;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .kula-input-group {
            display: flex;
            gap: 10px;
        }
        .kula-chat-input {
            flex: 1;
            background: #0f172a;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            padding: 10px 14px;
            color: #ffffff;
            font-size: 13.5px;
            outline: none;
        }
        .kula-chat-input:focus {
            border-color: #6366f1;
        }
        .kula-send-btn {
            background: #6366f1;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 0 16px;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s ease;
        }
        .kula-send-btn:hover { opacity: 0.9; }

        .kula-history-header {
            padding: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .kula-history-search-input {
            width: 100%;
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            padding: 6px 12px;
            color: #ffffff;
            font-size: 12px;
            outline: none;
        }
        .kula-history-search-input:focus {
            border-color: #6366f1;
        }

        .kula-history-list {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .kula-history-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 8px;
            padding: 8px 10px;
            color: #cbd5e1;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .kula-history-item:hover {
            background: rgba(99, 102, 241, 0.15);
            border-color: rgba(99, 102, 241, 0.4);
            color: #ffffff;
        }
        .kula-history-prompt-title {
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .kula-history-time {
            font-size: 10px;
            color: #64748b;
        }

        .kula-hdr-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #cbd5e1;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .kula-hdr-btn:hover {
            background: rgba(99, 102, 241, 0.25);
            border-color: #6366f1;
            color: #ffffff;
        }

        @media (max-width: 768px) {
            #kula-ai-drawer, #kula-ai-drawer.history-open { width: 100vw; }
            #kula-ai-trigger-btn { bottom: 16px; right: 16px; padding: 10px 16px; font-size: 13px; }
            .kula-drawer-content-body { flex-direction: column; }
            #kula-chat-history-panel { width: 100%; height: 250px; border-right: none; border-bottom: 1px solid rgba(255,255,255,0.1); }
        }
    </style>

    <!-- Floating Button removed per user directive -->

    <!-- Drawer Overlay -->
    <div id="kula-ai-drawer-overlay" onclick="KulaAIChat.close()"></div>

    <!-- Drawer Panel -->
    <div id="kula-ai-drawer" style="position: fixed;">
        <div class="kula-drawer-header">
            <div class="kula-drawer-title">
                <i class="fa-solid fa-brain" style="color: #10b981; font-size: 18px; margin-right: 6px;"></i> <span>KulaAI</span>
            </div>
            <div style="display: flex; align-items: center; gap: 6px;">
                <button type="button" class="kula-hdr-btn" onclick="KulaAIChat.newChat()" title="Start New Chat">
                    <i class="fa-solid fa-plus" style="color: #10b981;"></i> New
                </button>
                <button type="button" class="kula-hdr-btn" onclick="KulaAIChat.toggleHistory()" title="ChatGPT-Style Search History">
                    <i class="fa-solid fa-clock-rotate-left" style="color: #6366f1;"></i> History
                </button>
                <button type="button" class="kula-drawer-close" onclick="KulaAIChat.close()">&times;</button>
            </div>
        </div>

        <div class="kula-drawer-content-body">
            <!-- ChatGPT Style History Sidebar Panel (Left Side-by-Side) -->
            <div id="kula-chat-history-panel">
                <div class="kula-history-header">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 12.5px; font-weight: 700; color: #f8fafc;"><i class="fa-solid fa-clock-rotate-left" style="color: #818cf8; margin-right: 4px;"></i> Search History</span>
                        <button type="button" onclick="KulaAIChat.toggleHistory()" style="background:none; border:none; color:#94a3b8; font-size:16px; cursor:pointer;">&times;</button>
                    </div>
                    <input type="text" id="kula-history-search" class="kula-history-search-input" placeholder="🔍 Search past chats..." onkeyup="KulaAIChat.filterHistory(this.value)" autocomplete="off">
                </div>
                <div class="kula-history-list" id="kula-history-list-items">
                    <div style="text-align:center; padding:20px; color:#64748b; font-size:12px;">Loading past searches...</div>
                </div>
            </div>

            <!-- Main Chat Stream & Footer Input (Right Side) -->
            <div class="kula-chat-main-wrapper">
                <div class="kula-drawer-body" id="kula-chat-stream">
                    <div class="kula-msg kula-msg-ai">
                        👋 Hello! I am your <b>KulaAI Assistant</b>. Ask me anything — from live farm database metrics and mortality analysis to complete agribusiness business plans, livestock disease guides, feed formulas, and general questions!
                    </div>

                    <div class="kula-quick-prompts">
                        <button type="button" class="kula-chip" onclick="KulaAIChat.sendQuick('Write a comprehensive business plan for a 1,000 layer poultry farm including ROI and financial projections.')">📋 Business Plan</button>
                        <button type="button" class="kula-chip" onclick="KulaAIChat.sendQuick('What are the best practices for feed formulation and bio-security in chicken farming?')">🌾 Feed &amp; Health Guide</button>
                        <button type="button" class="kula-chip" onclick="KulaAIChat.sendQuick('Which batches currently have the highest mortality rate on our farm?')">🔴 Mortality Risk (Live Data)</button>
                        <button type="button" class="kula-chip" onclick="KulaAIChat.sendQuick('Which vaccinations are due this week across all sheds?')">💉 Vaccination Schedule</button>
                        <button type="button" class="kula-chip" onclick="KulaAIChat.sendQuick('Which clients owe us money and what is the total outstanding balance?')">💰 Debtors &amp; Finance</button>
                    </div>

                </div>

                <div class="kula-drawer-footer">
                    <!-- Document Upload Row -->
                    <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                        <label for="kula-doc-upload" style="flex: 1; display: flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.04); border: 1px dashed rgba(255,255,255,0.18); border-radius: 8px; padding: 7px 12px; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.borderColor='#6366f1'" onmouseout="this.style.borderColor='rgba(255,255,255,0.18)'">
                            <span style="font-size: 16px;">📎</span>
                            <span id="kula-upload-label" style="font-size: 12px; color: #94a3b8; font-weight: 500; flex: 1;">Upload PDF or Image Report for AI extraction...</span>
                        </label>
                        <input type="file" id="kula-doc-upload" accept=".pdf,.jpg,.jpeg,.png,.webp" style="display:none;" onchange="KulaAIChat.onDocumentSelected(this)">
                        <button type="button" id="kula-extract-btn" onclick="KulaAIChat.uploadDocument()" style="display:none; background: linear-gradient(135deg, #047857, #059669); color: #fff; border: none; border-radius: 8px; padding: 7px 14px; font-size: 12px; font-weight: 700; cursor: pointer; white-space: nowrap;">
                            <i class="fa-solid fa-file-import"></i> Extract
                        </button>
                    </div>
                    <form id="kula-chat-form" onsubmit="KulaAIChat.handleSubmit(event)">
                        <div class="kula-input-group">
                            <input type="text" id="kula-chat-input-text" class="kula-chat-input" placeholder="Ask KulaAI anything about your farm..." autocomplete="off" />
                            <button type="submit" class="kula-send-btn">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Ingestion Preview Modal -->
    <div id="kula-ingestion-modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(15,23,42,0.8); backdrop-filter:blur(6px); z-index:9999999; align-items:center; justify-content:center;">
        <div style="background:#1e293b; border:1px solid rgba(255,255,255,0.12); border-radius:16px; width:90%; max-width:780px; max-height:88vh; display:flex; flex-direction:column; box-shadow:0 25px 60px rgba(0,0,0,0.6);">
            <div style="padding:18px 22px; border-bottom:1px solid rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:space-between; flex-shrink:0;">
                <div style="font-size:16px; font-weight:700; color:#f8fafc; display:flex; align-items:center; gap:10px;">
                    <span style="font-size:20px;">📄</span> AI Data Extraction Preview
                </div>
                <button onclick="KulaAIChat.closeIngestionModal()" style="background:none; border:none; color:#94a3b8; font-size:22px; cursor:pointer;">&times;</button>
            </div>
            <div id="kula-ingestion-body" style="flex:1; overflow-y:auto; padding:20px;">
                <!-- Dynamically populated by JS -->
            </div>
            <div style="padding:16px 22px; border-top:1px solid rgba(255,255,255,0.08); display:flex; justify-content:flex-end; gap:10px; flex-shrink:0;" id="kula-ingestion-actions">
                <button onclick="KulaAIChat.closeIngestionModal()" style="background:rgba(255,255,255,0.08); color:#cbd5e1; border:1px solid rgba(255,255,255,0.12); padding:9px 20px; border-radius:8px; font-weight:600; cursor:pointer; font-size:13px;">
                    ❌ Cancel
                </button>
                <button id="kula-confirm-import-btn" onclick="KulaAIChat.confirmImport()" style="background:linear-gradient(135deg, #10b981, #059669); color:#fff; border:none; padding:9px 22px; border-radius:8px; font-weight:700; cursor:pointer; font-size:13px;">
                    ✅ Confirm & Save to KulaCRM
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const KulaAIChat = {
        isOpen: false,
        baseUrl: '<?= base_url() ?>',
        _ingestionData: null,

        onDocumentSelected(input) {
            const file = input.files[0];
            if (!file) return;
            const label = document.getElementById('kula-upload-label');
            const btn   = document.getElementById('kula-extract-btn');
            label.textContent = '📄 ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            label.style.color = '#a5b4fc';
            btn.style.display = 'inline-flex';
        },

        uploadDocument() {
            const fileInput = document.getElementById('kula-doc-upload');
            const file = fileInput.files[0];
            if (!file) return;

            const btn = document.getElementById('kula-extract-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Extracting...';

            const formData = new FormData();
            formData.append('document', file);

            $.ajax({
                url: '<?= site_url("kula_ai/upload_document") ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: (data) => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-file-import"></i> Extract';
                    if (data && data.status) {
                        this._ingestionData = data;
                        this.showIngestionPreview(data);
                    } else {
                        alert('❌ Extraction failed: ' + (data.error || 'Unknown error. Please try a different file.'));
                    }
                },
                error: () => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-file-import"></i> Extract';
                    alert('❌ Upload failed. Please check your connection and try again.');
                }
            });
        },

        showIngestionPreview(data) {
            const modal = document.getElementById('kula-ingestion-modal');
            const body  = document.getElementById('kula-ingestion-body');
            modal.style.display = 'flex';

            const badge = (cat, count) => {
                const colors = {sales:'#10b981', purchases:'#3b82f6', deaths:'#ef4444', vaccinations:'#a855f7'};
                const icons  = {sales:'🟢', purchases:'🔵', deaths:'🔴', vaccinations:'💉'};
                return `<span style="background:${colors[cat]}22; color:${colors[cat]}; border:1px solid ${colors[cat]}44; padding:3px 10px; border-radius:12px; font-size:11px; font-weight:700; margin-right:6px;">${icons[cat]} ${count} ${cat}</span>`;
            };

            let html = `<div style="margin-bottom:16px; padding:12px 16px; background:rgba(99,102,241,0.1); border:1px solid rgba(99,102,241,0.3); border-radius:10px;">
                <div style="font-size:13px; font-weight:700; color:#f8fafc; margin-bottom:6px;">📄 Extracted from: <span style="color:#a5b4fc;">${this.escapeHtml(data.file_name)}</span></div>
                <div>${badge('sales', data.sales.length)}${badge('purchases', data.purchases.length)}${badge('deaths', data.deaths.length)}${badge('vaccinations', data.vaccinations.length)}</div>
            </div>`;

            const buildTable = (title, icon, color, rows, columns) => {
                if (!rows || rows.length === 0) return '';
                let t = `<div style="margin-bottom:20px;">
                    <h4 style="font-size:13.5px; font-weight:700; color:${color}; margin:0 0 10px 0;">${icon} ${title} (${rows.length} records)</h4>
                    <div style="overflow-x:auto; border-radius:10px; border:1px solid rgba(255,255,255,0.08);">
                    <table style="width:100%; border-collapse:collapse; font-size:12px;">
                    <thead><tr style="background:${color}22; color:${color};">
                    ${columns.map(c => `<th style="padding:8px 10px; text-align:left; font-weight:700;">${c.label}</th>`).join('')}
                    </tr></thead><tbody>`;
                rows.forEach(r => {
                    t += `<tr style="border-bottom:1px solid rgba(255,255,255,0.05);">`;
                    columns.forEach(c => {
                        const val = r[c.key] ?? '—';
                        t += `<td style="padding:8px 10px; color:#cbd5e1;">${this.escapeHtml(String(val))}</td>`;
                    });
                    t += `</tr>`;
                });
                t += `</tbody></table></div></div>`;
                return t;
            };

            html += buildTable('Sales', '🟢', '#10b981', data.sales, [
                {key:'date',label:'Date'},{key:'client_name',label:'Client'},{key:'livestock_type',label:'Type'},
                {key:'quantity',label:'Qty'},{key:'unit_price',label:'Unit Price'},{key:'total_amount',label:'Total'}
            ]);
            html += buildTable('Purchases', '🔵', '#3b82f6', data.purchases, [
                {key:'date',label:'Date'},{key:'supplier_name',label:'Supplier'},{key:'livestock_type',label:'Type'},
                {key:'quantity',label:'Qty'},{key:'unit_price',label:'Unit Price'},{key:'total_amount',label:'Total'}
            ]);
            html += buildTable('Deaths', '🔴', '#ef4444', data.deaths, [
                {key:'date',label:'Date'},{key:'batch_name',label:'Batch'},{key:'shed_name',label:'Shed'},
                {key:'quantity',label:'Deaths'},{key:'reason',label:'Reason'}
            ]);
            html += buildTable('Vaccinations', '💉', '#a855f7', data.vaccinations, [
                {key:'date',label:'Date'},{key:'batch_name',label:'Batch'},{key:'shed_name',label:'Shed'},
                {key:'vaccine_name',label:'Vaccine'},{key:'quantity',label:'Qty'},{key:'notes',label:'Notes'}
            ]);

            if (data.total_records === 0) {
                html += `<div style="text-align:center; padding:30px; color:#64748b;">
                    <div style="font-size:40px; margin-bottom:10px;">🔍</div>
                    <div style="font-size:13px;">No structured livestock data detected in this document.<br>Try a clearer PDF or image with visible data tables.</div>
                </div>`;
                document.getElementById('kula-confirm-import-btn').style.display = 'none';
            } else {
                document.getElementById('kula-confirm-import-btn').style.display = 'inline-flex';
            }

            body.innerHTML = html;
        },

        closeIngestionModal() {
            document.getElementById('kula-ingestion-modal').style.display = 'none';
            this._ingestionData = null;
            // Reset file input
            const fi = document.getElementById('kula-doc-upload');
            if (fi) fi.value = '';
            const label = document.getElementById('kula-upload-label');
            if (label) { label.textContent = 'Upload PDF or Image Report for AI extraction...'; label.style.color = '#94a3b8'; }
            const btn = document.getElementById('kula-extract-btn');
            if (btn) btn.style.display = 'none';
        },

        confirmImport() {
            if (!this._ingestionData) return;

            const confirmBtn = document.getElementById('kula-confirm-import-btn');
            confirmBtn.disabled = true;
            confirmBtn.textContent = '⏳ Saving...';

            $.ajax({
                url: '<?= site_url("kula_ai/confirm_import") ?>',
                type: 'POST',
                data: {
                    data: JSON.stringify(this._ingestionData),
                    types: ['sales', 'purchases', 'deaths', 'vaccinations']
                },
                dataType: 'json',
                success: (res) => {
                    this.closeIngestionModal();
                    if (res && res.status) {
                        const stream = document.getElementById('kula-chat-stream');
                        let summary = `<div class="kula-msg kula-msg-ai"><div style="margin-bottom:8px;">✅ <strong>Document Import Successful!</strong><br><span style="color:#94a3b8; font-size:12px;">${res.total_saved} records saved to KulaCRM.</span></div>`;
                        const icons = {sales:'🟢 Sales', purchases:'🔵 Purchases', deaths:'🔴 Deaths', vaccinations:'💉 Vaccinations'};
                        Object.entries(res.results || {}).forEach(([key, r]) => {
                            summary += `<div style="font-size:12px; color:#94a3b8;">• ${icons[key] || key}: ${r.saved} saved${r.skipped > 0 ? ', ' + r.skipped + ' skipped' : ''}</div>`;
                        });
                        summary += `</div>`;
                        stream.insertAdjacentHTML('beforeend', summary);
                        stream.scrollTop = stream.scrollHeight;
                    } else {
                        alert('Import error: ' + (res.error || 'Unknown error.'));
                    }
                    confirmBtn.disabled = false;
                    confirmBtn.textContent = '✅ Confirm & Save to KulaCRM';
                },
                error: () => {
                    alert('Failed to save data. Please try again.');
                    confirmBtn.disabled = false;
                    confirmBtn.textContent = '✅ Confirm & Save to KulaCRM';
                }
            });
        },

        toggle() {
            this.isOpen ? this.close() : this.open();
        },

        open(initialPrompt = '') {
            document.getElementById('kula-ai-drawer-overlay').classList.add('active');
            document.getElementById('kula-ai-drawer').classList.add('active');
            this.isOpen = true;

            if (initialPrompt) {
                this.sendQuick(initialPrompt);
            }
        },

        close() {
            document.getElementById('kula-ai-drawer-overlay').classList.remove('active');
            document.getElementById('kula-ai-drawer').classList.remove('active');
            this.isOpen = false;
        },

        sendQuick(prompt) {
            document.getElementById('kula-chat-input-text').value = prompt;
            this.handleSubmit(new Event('submit'));
        },

        historyLoaded: false,
        rawHistory: [],

        toggleHistory() {
            const drawer = document.getElementById('kula-ai-drawer');
            if (drawer.classList.contains('history-open')) {
                drawer.classList.remove('history-open');
            } else {
                drawer.classList.add('history-open');
                if (!this.historyLoaded) {
                    this.fetchHistory();
                }
            }
        },

        newChat() {
            const stream = document.getElementById('kula-chat-stream');
            stream.innerHTML = `
                <div class="kula-msg kula-msg-ai">
                    👋 Hello! I am your <b>KulaAI Assistant</b>. I am connected directly to your live KulaCRM database.
                    How can I assist you today?
                </div>
                <div class="kula-quick-prompts">
                    <button type="button" class="kula-chip" onclick="KulaAIChat.sendQuick('How many animals do we currently have?')">🐄 Animals Count</button>
                    <button type="button" class="kula-chip" onclick="KulaAIChat.sendQuick('Which batches have the highest mortality?')">🔴 Mortality Risk</button>
                    <button type="button" class="kula-chip" onclick="KulaAIChat.sendQuick('Which vaccinations are due this week?')">💉 Vaccine Schedule</button>
                    <button type="button" class="kula-chip" onclick="KulaAIChat.sendQuick('Which food stock will run out first?')">🌾 Feed Forecast</button>
                    <button type="button" class="kula-chip" onclick="KulaAIChat.sendQuick('Which customers owe us money?')">💰 Outstanding Debtors</button>
                </div>
            `;
            const drawer = document.getElementById('kula-ai-drawer');
            if (drawer) drawer.classList.remove('history-open');
        },

        fetchHistory() {
            const container = document.getElementById('kula-history-list-items');
            container.innerHTML = '<div style="text-align:center; padding:20px; color:#64748b; font-size:12px;"><i class="fa-solid fa-spinner fa-spin"></i> Loading past searches...</div>';

            const endpointUrl = '<?= site_url("kula_ai/history") ?>';

            $.ajax({
                url: endpointUrl,
                type: 'GET',
                dataType: 'json',
                success: (data) => {
                    this.historyLoaded = true;
                    if (data && data.status && Array.isArray(data.history)) {
                        this.rawHistory = data.history;
                        this.renderHistoryList(this.rawHistory);
                    } else {
                        container.innerHTML = '<div style="text-align:center; padding:20px; color:#64748b; font-size:12px;">No past search history.</div>';
                    }
                },
                error: () => {
                    // Try direct relative fallback
                    $.ajax({
                        url: '<?= base_url("kula_ai/history") ?>',
                        type: 'GET',
                        dataType: 'json',
                        success: (data) => {
                            this.historyLoaded = true;
                            if (data && data.status && Array.isArray(data.history)) {
                                this.rawHistory = data.history;
                                this.renderHistoryList(this.rawHistory);
                            } else {
                                container.innerHTML = '<div style="text-align:center; padding:20px; color:#64748b; font-size:12px;">No past search history.</div>';
                            }
                        },
                        error: () => {
                            container.innerHTML = '<div style="text-align:center; padding:20px; color:#fca5a5; font-size:12px;">Unable to load history. Please refresh the page and log in.</div>';
                        }
                    });
                }
            });
        },

        renderHistoryList(items) {
            const container = document.getElementById('kula-history-list-items');
            if (!items || items.length === 0) {
                container.innerHTML = '<div style="text-align:center; padding:20px; color:#64748b; font-size:12px;">No matching searches.</div>';
                return;
            }

            let html = '';
            items.forEach(item => {
                const escapedPrompt = this.escapeHtml(item.prompt);
                html += `
                    <div class="kula-history-item" onclick="KulaAIChat.loadHistoryItem('${escapedPrompt.replace(/'/g, "\\'")}')">
                        <div class="kula-history-prompt-title">💬 ${escapedPrompt}</div>
                        <div class="kula-history-time">${item.created_at || 'Recent'}</div>
                    </div>
                `;
            });
            container.innerHTML = html;
        },

        filterHistory(query) {
            if (!this.rawHistory) return;
            const q = query.toLowerCase().trim();
            const filtered = this.rawHistory.filter(item => (item.prompt || '').toLowerCase().includes(q));
            this.renderHistoryList(filtered);
        },

        loadHistoryItem(prompt) {
            const drawer = document.getElementById('kula-ai-drawer');
            if (drawer) drawer.classList.remove('history-open');
            this.sendQuick(prompt);
        },

        escapeHtml(str) {
            return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        },

        escapeAttr(str) {
            return String(str || '').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        },

        handleSubmit(e) {
            if (e) e.preventDefault();
            const input = document.getElementById('kula-chat-input-text');
            const prompt = input.value.trim();
            if (!prompt) return;

            this.appendUserMessage(prompt);
            input.value = '';

            const stream = document.getElementById('kula-chat-stream');
            const loadingId = 'loading-' + Date.now();
            const loadingMsg = document.createElement('div');
            loadingMsg.className = 'kula-msg kula-msg-ai';
            loadingMsg.id = loadingId;
            loadingMsg.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="color: #10b981;"></i> <i>KulaAI is checking live farm data...</i>';
            stream.appendChild(loadingMsg);
            stream.scrollTop = stream.scrollHeight;

            const primaryUrl = '<?= site_url("kula_ai/chat") ?>';
            const fallbackUrl = KulaAIChat.baseUrl + 'kula_ai/chat';

            const executeChatRequest = (targetUrl, isRetry = false) => {
                $.ajax({
                    url: targetUrl,
                    type: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    data: { prompt: prompt },
                    dataType: 'json',
                    success: function(data) {
                        const elem = document.getElementById(loadingId);
                        if (data && data.status) {
                            const parsedHtml = KulaAIChat.formatMarkdown(data.response);
                            elem.innerHTML = parsedHtml + `
                                <div style="margin-top: 12px; padding-top: 8px; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: flex-end;">
                                    <form action="${KulaAIChat.baseUrl}kula_ai/export_pdf" method="POST" target="_blank" style="margin:0;">
                                        <input type="hidden" name="content" value="${KulaAIChat.escapeAttr(parsedHtml)}">
                                        <input type="hidden" name="title" value="KulaAI Executive Report">
                                        <button type="submit" style="background: rgba(99, 102, 241, 0.2); color: #a5b4fc; border: 1px solid rgba(99, 102, 241, 0.4); padding: 5px 12px; border-radius: 8px; font-size: 11.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s ease;">
                                            <i class="fa-solid fa-file-pdf" style="color: #ef4444;"></i> Export PDF
                                        </button>
                                    </form>
                                </div>
                            `;
                        } else if (data && data.response) {
                            elem.innerHTML = KulaAIChat.formatMarkdown(data.response);
                        } else if (data && data.error) {
                            elem.innerHTML = '⚠️ ' + data.error;
                        } else {
                            elem.innerHTML = '⚠️ Unable to retrieve answer. Please refresh and try again.';
                        }
                        stream.scrollTop = stream.scrollHeight;
                    },
                    error: function(xhr, status, error) {
                        if (!isRetry && targetUrl !== fallbackUrl) {
                            executeChatRequest(fallbackUrl, true);
                            return;
                        }
                        const elem = document.getElementById(loadingId);
                        if (elem) {
                            let msg = 'Service connection error. Please refresh and log in to use KulaAI.';
                            if (xhr && xhr.responseJSON) {
                                if (xhr.responseJSON.response) msg = xhr.responseJSON.response;
                                else if (xhr.responseJSON.error) msg = xhr.responseJSON.error;
                            } else if (xhr && xhr.responseText) {
                                try {
                                    const parsed = JSON.parse(xhr.responseText);
                                    if (parsed && parsed.response) msg = parsed.response;
                                    else if (parsed && parsed.error) msg = parsed.error;
                                } catch(e) {}
                            }
                            elem.innerHTML = KulaAIChat.formatMarkdown(msg);
                        }
                        stream.scrollTop = stream.scrollHeight;
                    }
                });
            };

            executeChatRequest(primaryUrl);
        },

        appendUserMessage(text) {
            const stream = document.getElementById('kula-chat-stream');
            const msg = document.createElement('div');
            msg.className = 'kula-msg kula-msg-user';
            msg.innerText = text;
            stream.appendChild(msg);
            stream.scrollTop = stream.scrollHeight;
        },

        formatMarkdown(txt) {
            if (!txt) return '';

            // 1. Parse Markdown Tables (| Header | Header |\n|---|---|\n| Cell | Cell |)
            let lines = txt.split('\n');
            let inTable = false;
            let tableHtml = '';
            let parsedLines = [];

            for (let i = 0; i < lines.length; i++) {
                let line = lines[i].trim();

                if (line.startsWith('|') && line.endsWith('|')) {
                    // Skip divider rows (|---|---| or |:---|:---|)
                    if (/^\|(\s*:?-+:?\s*\|)+$/.test(line)) {
                        continue;
                    }

                    let cells = line.split('|').slice(1, -1).map(c => c.trim());

                    if (!inTable) {
                        inTable = true;
                        tableHtml = '<div style="overflow-x:auto;margin:12px 0;"><table style="width:100%;border-collapse:collapse;font-size:12.5px;border-radius:10px;overflow:hidden;border:1px solid rgba(255,255,255,0.12);background:rgba(15,23,42,0.6);">';
                        tableHtml += '<thead><tr style="background:rgba(99,102,241,0.25);color:#a5b4fc;border-bottom:1px solid rgba(255,255,255,0.12);">';
                        cells.forEach(cell => {
                            tableHtml += '<th style="padding:8px 12px;text-align:left;font-weight:700;white-space:nowrap;">' + cell + '</th>';
                        });
                        tableHtml += '</tr></thead><tbody>';
                    } else {
                        tableHtml += '<tr style="border-bottom:1px solid rgba(255,255,255,0.06);">';
                        cells.forEach(cell => {
                            let styledCell = cell;
                            // Badge formatting for percentages (e.g. 10%, 5.5%)
                            if (/^\d+(\.\d+)?%$/.test(cell)) {
                                let num = parseFloat(cell);
                                let badgeColor = num >= 10 ? '#ef4444' : (num >= 5 ? '#f59e0b' : '#10b981');
                                styledCell = `<span style="background:${badgeColor}22;color:${badgeColor};padding:3px 8px;border-radius:12px;font-weight:800;border:1px solid ${badgeColor}44;display:inline-block;">${cell}</span>`;
                            }
                            tableHtml += '<td style="padding:8px 12px;color:#e2e8f0;white-space:nowrap;">' + styledCell + '</td>';
                        });
                        tableHtml += '</tr>';
                    }
                } else {
                    if (inTable) {
                        inTable = false;
                        tableHtml += '</tbody></table></div>';
                        parsedLines.push(tableHtml);
                        tableHtml = '';
                    }
                    parsedLines.push(line);
                }
            }

            if (inTable) {
                tableHtml += '</tbody></table></div>';
                parsedLines.push(tableHtml);
            }

            let html = parsedLines.join('\n');

            // 2. Format Headings, Bullet Lists, and Bold Text
            html = html
                .replace(/^#### (.*$)/gim, '<h5 style="margin:12px 0 6px 0;color:#c084fc;font-weight:800;font-size:13.5px;">$1</h5>')
                .replace(/^### (.*$)/gim, '<h4 style="margin:14px 0 6px 0;color:#818cf8;font-weight:800;font-size:14.5px;border-bottom:1px solid rgba(255,255,255,0.08);padding-bottom:4px;">$1</h4>')
                .replace(/^## (.*$)/gim, '<h3 style="margin:16px 0 8px 0;color:#38bdf8;font-weight:800;font-size:16px;">$1</h3>')
                .replace(/^# (.*$)/gim, '<h2 style="margin:18px 0 10px 0;color:#ffffff;font-weight:800;font-size:18px;">$1</h2>')
                .replace(/---/g, '<hr style="border:none;border-top:1px solid rgba(255,255,255,0.1);margin:14px 0;"/>')
                .replace(/\*\*(.*?)\*\*/g, '<strong style="color:#ffffff;font-weight:700;">$1</strong>')
                .replace(/\*(.*?)\*/g, '<em style="color:#cbd5e1;">$1</em>')
                .replace(/^[\*\-] (.*$)/gim, '<div style="display:flex;gap:8px;margin:4px 0;line-height:1.5;"><span style="color:#818cf8;font-weight:bold;">•</span><span>$1</span></div>')
                .replace(/\n\n/g, '<br/>');

            return html;
        }
    };
</script>
