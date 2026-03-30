

<div class="table-container" style="padding: 30px;">
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <h3 style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="building" size="18"></i>
                    School Information
                </h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 5px; color: #64748b;">School Name</label>
                        <input type="text" value="Classic Academy School" class="input" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #cbd5e1;" readonly />
                    </div>

                </div>
            </div>

            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <h3 style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="shield-check" size="18"></i>
                    System Verification
                </h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.9rem;">Database Status (SQLite)</span>
                        <span class="badge badge-paid">Connected</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.9rem;">Backend Type</span>
                        <span class="badge badge-paid">LAMP (PHP)</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.9rem;">Currency Code</span>
                        <span class="badge" style="background:#f1f5f9; color:#1e293b;">RWF</span>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary" onclick="alert('Settings changes saved.')" style="width: 100%; display: flex; justify-content: center; align-items: center; gap: 10px; padding: 12px;">
                <i data-lucide="save" size="18"></i>
                Save Configuration Changes
            </button>
        </div>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
