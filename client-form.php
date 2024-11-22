<?php require_once("./configs/globalconst.php"); ?>
<?php require_once(SYSROOT . 'app.php'); ?>
<?php require(DOCROOT . "layout" . DS . "header.php"); ?>

<div class="container mt-5 mb-5">
    <div class="row mb-3">
        <div class="col-12 bg-white border-rounded-20">
            <div class="px-5 py-5 bg-white">
                <h1>Add Client</h1>
                <hr>
                <div id="response-message"></div>
                <form action="<?php echo APP_URL; ?>actions/client_add.php" id="add-client-form" method="post">
                    <div class="form-group d-flex flex-column">
                        <label for="client_name">Client Name</label>
                        <input type="text" class="form-control" id="client_name" name="client_name" required>
                    </div>
                    <div class="form-group d-flex flex-column">
                        <label for="company_name">Company Name</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" required>
                    </div>
                    <div class="form-group d-flex flex-column">
                        <label for="country">Country</label>
                        <?php $countries = App::getConf('countries'); ?>
                        <select class="form-control" id="country" name="country" required>
                            <?php foreach ($countries as $k => $v) { ?>
                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group d-flex flex-column">
                        <label for="currency">Currency</label>
                        <?php $currencies = App::getConf('currencies'); ?>
                        <select class="form-control" id="currency" name="currency" required>
                            <?php foreach ($currencies as $k => $v) { ?>
                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php
                    App::loadClass('package', SYSROOT);
                    $packages = Package::getPackages();
                    $currencies = App::getConf('currencies');
                    ?>
                    <?php if ($packages) { ?>
                        <div class="form-group d-flex flex-column">
                            <label for="package">Package</label>
                            <select class="form-control" id="package" name="package" required>
                                <?php foreach ($packages as $package) { ?>
                                    <option value="<?php echo $package['id']; ?>"><?php echo $package['name'] . " - " . $package['price'] . ' ' . $currencies[$package['currency']]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    <?php } ?>
                    <div class="form-group d-flex flex-column">
                        <label for="vat_number">Vat Number</label>
                        <input type="text" class="form-control" id="vat_number" name="vat_number" required>
                    </div>

                    <div id="contact-persons-section" class="mb-3 mt-3">
                        <h5>Contact Persons</h5>
                        <div class="contact-person mb-3">
                            <h6>Contact Person 1</h6>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="contact_persons[0][firstname]" placeholder="First Name">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="contact_persons[0][lastname]" placeholder="Last Name">
                                </div>
                                <div class="col-md-3">
                                    <input type="email" class="form-control" name="contact_persons[0][email]" placeholder="Email">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="contact_persons[0][phone]" placeholder="Phone">
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                    </div>
                    <button type="button" id="add-contact-person" class="btn btn-secondary mt-3">Add Another Contact</button>

                    <input type="submit" class="btn btn-primary mt-3" value="Save">
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Add Dynamic Contact Person Fields -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let contactPersonIndex = 1;

        document.getElementById('add-contact-person').addEventListener('click', function() {
            if (contactPersonIndex >= 3) {
                // Show alert in modal
                showAlertModal('You can only add up to 3 contact persons.');
                return;
            }

            const contactPersonsSection = document.getElementById('contact-persons-section');
            const newContactPerson = `
                <div class="contact-person mb-3">
                    <h6>Contact Person ${contactPersonIndex + 1}</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="contact_persons[${contactPersonIndex}][firstname]" placeholder="First Name">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="contact_persons[${contactPersonIndex}][lastname]" placeholder="Last Name">
                        </div>
                        <div class="col-md-3">
                            <input type="email" class="form-control" name="contact_persons[${contactPersonIndex}][email]" placeholder="Email">
                        </div>
                         <div class="col-md-3">
                            <input type="text" class="form-control" name="contact_persons[${contactPersonIndex}][phone]" placeholder="Phone">
                        </div>
                    </div>
                    <hr class="my-4">
                </div>`;
            contactPersonsSection.insertAdjacentHTML('beforeend', newContactPerson);
            contactPersonIndex++;
        });
    });

    function showAlertModal(message) {
        const modalHtml = `
            <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="alertModalLabel">Alert</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ${message}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const modal = new bootstrap.Modal(document.getElementById('alertModal'));
        modal.show();

        // Remove modal after it has been closed
        document.getElementById('alertModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    }
</script>

<?php require(DOCROOT . "layout" . DS . "footer.php"); ?>