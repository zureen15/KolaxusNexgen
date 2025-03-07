function openUpdateModal(voucherId, programName, loyaltyType, voucherCode, description, startDate, expiryDate, image) {
    Swal.fire({
        title: 'Update Voucher or Discount',
        html: `
            <form id="updateForm">
                <div class="form-group">
                    <label>Program Name</label>
                    <input type="text" class="form-control" name="program_name" value="${programName}" required>
                </div>
                <div class="form-group">
                    <label>Loyalty Type</label>
                    <select class="form-control" name="loyalty_type" required>
                        <option value="Food & Beverage" ${loyaltyType === 'Food & Beverage' ? 'selected' : ''}>Food & Beverage</option>
                        <option value="Entertainment" ${loyaltyType === 'Entertainment' ? 'selected' : ''}>Entertainment</option>
                        <option value="Health & Beauty" ${loyaltyType === 'Health & Beauty' ? 'selected' : ''}>Health & Beauty</option>
                        <option value="Shopping" ${loyaltyType === 'Shopping' ? 'selected' : ''}>Shopping</option>
                        <option value="Travel & Services" ${loyaltyType === 'Travel & Services' ? 'selected' : ''}>Travel & Services</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Voucher Code</label>
                    <input type="text" class="form-control" name="voucher_code" value="${voucherCode}" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" required>${description}</textarea>
                </div>
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" class="form-control" name="start_date" value="${startDate}" required>
                </div>
                <div class="form-group">
                    <label>Expiry Date</label>
                    <input type="date" class="form-control" name="expiry_date" value="${expiryDate}" required>
                </div>
                <div class="form-group">
                    <label>Current Image</label>
                    <div>
                        <img src="${image}" alt="Voucher Image" style="max-width: 100%; height: auto;">
                    </div>
                </div>
                <input type="hidden" name="voucher_id" value="${voucherId}">
                <input type="hidden" name="existing_image" value="${image}">
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Update',
        preConfirm: () => {
            const form = document.getElementById('updateForm');
            const formData = new FormData(form);
            const programName = formData.get('program_name');
            const loyaltyType = formData.get('loyalty_type');
            const voucherCode = formData.get('voucher_code');
            const description = formData.get('description');
            const startDate = formData.get('start_date');
            const expiryDate = formData.get('expiry_date');

            // Simple validation
            if (!programName || !loyaltyType || !voucherCode || !description || !startDate || !expiryDate) {
                Swal.showValidationMessage('Please fill in all fields.');
                return false;
            }

            // If validation passes, return the form data
            return fetch('../backend/admin_loyalty/update_loyalty.php', {
                method: 'POST',
                body: formData
            }).then(response => {
                if (!response.ok) {
                    throw new Error(response.statusText);
                }
                return response.json();
            }).catch(error => {
                Swal.showValidationMessage(`Request failed: ${error}`);
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Updated!', '', 'success');
            // Optionally, refresh the page or update the table to reflect the changes without a full page reload.
        }
    });
}