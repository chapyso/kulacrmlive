<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- Subheader -->
        <div class="kula-subheader-bar" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 22px; flex-wrap: wrap; gap: 12px; max-width: 100%;">
            <div>
                <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; border: none; padding: 0; text-transform: none; letter-spacing: -0.4px;">✨ KulaAI Global System Configuration</h2>
                <span style="font-size: 13px; color: #64748b; font-weight: 500;">Manage AI provider credentials, model selection, global access switches, and tenant permissions</span>
            </div>
        </div>

        <?php if ($this->session->flashdata('feedback')): ?>
            <div class="alert alert-success" style="border-radius: 12px; font-weight: 600;">
                <i class="fa-solid fa-circle-check" style="margin-right: 6px;"></i> <?php echo $this->session->flashdata('feedback'); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; background: #ffffff; padding: 28px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <form action="<?php echo base_url('superadmin/ai_settings'); ?>" method="post">
                        <!-- Global Enable Toggle -->
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <h4 style="margin: 0 0 4px 0; font-size: 15px; font-weight: 800; color: #0f172a;">Global AI Engine Status</h4>
                                <p style="margin: 0; font-size: 12.5px; color: #64748b;">Enable or disable KulaAI features system-wide across all SaaS tenants</p>
                            </div>
                            <div>
                                <label class="switch" style="position: relative; display: inline-block; width: 50px; height: 26px; margin: 0;">
                                    <input type="checkbox" name="ai_enabled" value="1" <?php echo (!empty($settings->ai_enabled)) ? 'checked' : ''; ?>>
                                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px;"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Provider Selection -->
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="font-weight: 700; font-size: 13px; color: #334155;">Active AI Provider Engine</label>
                            <select name="default_provider" class="form-control" style="border-radius: 10px; height: 42px; font-weight: 600;" onchange="updateModelOptions(this.value)">
                                <option value="gemini" <?php echo ($settings->default_provider === 'gemini') ? 'selected' : ''; ?>>Google Gemini API (Recommended - Fast & Powerful)</option>
                                <option value="openai" <?php echo ($settings->default_provider === 'openai') ? 'selected' : ''; ?>>OpenAI ChatGPT (GPT-4o / GPT-4o-mini)</option>
                                <option value="groq" <?php echo ($settings->default_provider === 'groq') ? 'selected' : ''; ?>>Groq Cloud API (Llama 3.3 70B Ultra-Fast)</option>
                                <option value="ollama" <?php echo ($settings->default_provider === 'ollama') ? 'selected' : ''; ?>>Local Ollama Server (100% Free & Offline)</option>
                            </select>
                        </div>

                        <!-- API Key Input -->
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="font-weight: 700; font-size: 13px; color: #334155;">System API Key</label>
                            <input type="password" name="api_key" value="<?php echo htmlspecialchars($settings->api_key ?? ''); ?>" placeholder="Enter secret API key..." class="form-control" style="border-radius: 10px; height: 42px; font-family: monospace;" autocomplete="new-password">
                            <span style="font-size: 11.5px; color: #64748b; margin-top: 4px; display: block;">API Keys are encrypted server-side and never exposed to tenant browsers. Leave blank to keep existing key.</span>
                        </div>

                        <!-- Model Selection -->
                        <div class="form-group" style="margin-bottom: 24px;">
                            <label style="font-weight: 700; font-size: 13px; color: #334155;">Model Selection</label>
                            <input type="text" name="model_name" id="model_name_input" value="<?php echo htmlspecialchars($settings->model_name ?? 'gemini-1.5-flash'); ?>" class="form-control" style="border-radius: 10px; height: 42px; font-weight: 600;">
                        </div>

                        <!-- Allow Custom Tenant Keys -->
                        <div class="form-group" style="margin-bottom: 28px;">
                            <label style="font-weight: 700; font-size: 13px; color: #334155; display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" name="allow_tenant_custom_keys" value="1" <?php echo (!empty($settings->allow_tenant_custom_keys)) ? 'checked' : ''; ?>>
                                Allow Tenants to Bring Their Own Custom API Keys
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); border: none; border-radius: 10px; padding: 10px 24px; font-weight: 700; font-size: 14px;">
                            <i class="fa-solid fa-floppy-disk"></i> Save Global KulaAI Settings
                        </button>
                    </form>
                </div>
            </div>

            <!-- Side Card Information -->
            <div class="col-md-4 col-sm-12">
                <div class="panel" style="border-radius: 16px; border: 1px solid #e2e8f0; background: #0f172a; color: #f8fafc; padding: 24px;">
                    <h3 style="font-size: 16px; font-weight: 800; color: #ffffff; margin: 0 0 12px 0;">✨ Tier-Based AI Access</h3>
                    <p style="font-size: 13px; color: #cbd5e1; line-height: 1.5;">
                        Super Admin can control which subscription plans include KulaAI access in the <b>Subscription Tier Plans</b> section.
                    </p>
                    <hr style="border-color: rgba(255,255,255,0.1);">
                    <a href="<?php echo base_url('superadmin/plans'); ?>" class="btn btn-default btn-block" style="border-radius: 10px; font-weight: 700; background: #1e293b; color: #fff; border: 1px solid rgba(255,255,255,0.15);">
                        <i class="fa-solid fa-layer-group"></i> Manage Plan Tier AI Gates
                    </a>
                </div>
            </div>
        </div>
    </section>
</section>

<script>
function updateModelOptions(provider) {
    const modelInput = document.getElementById('model_name_input');
    if (provider === 'gemini') {
        modelInput.value = 'gemini-1.5-flash';
    } else if (provider === 'openai') {
        modelInput.value = 'gpt-4o-mini';
    } else if (provider === 'groq') {
        modelInput.value = 'llama-3.3-70b-versatile';
    } else if (provider === 'ollama') {
        modelInput.value = 'llama3';
    }
}
</script>
