$(document).ready(() => {

    const ACTIONS = {
        addClient: "actions/client_add.php",
        getClientsData: "actions/clients_get.php",
        getPackagesData: "actions/packages_get.php",
        getContactPersonsData: "actions/contact_persons_get.php", 
        getPackagesForCurrency: "actions/packages_get_currency.php",
        getAccountManagersClients: "actions/acc_managers_clients_get.php",
    } 

    const renderResponseAlert = (msg, type) => {
        return `<div class="alert alert-${type}">${msg}</div>`;
    }

    const getContent = async (url) => {
        return $.ajax({
            type: "GET",
            url: url,
            data: url,
            cache: false,
            async: true,
            dataType: "json"
        });
    }

    const postContent = async (url, data) => {
        return $.ajax({
            type: "POST",
            url: url,
            data: data,
            cache: false,
            async: true,
            dataType: "json"
        });
    }
    
    const packagesForCurrency = async (currency) => {
        try {
            const res = await getContent(ACTIONS.getPackagesForCurrency + "?currency=" + currency);
            if (res.status === "ok") {
                $('#package').empty();
                res.data.forEach((package) => {
                    $('#package').append(`<option value="${package.id}">${package.name}</option>`);
                });
            } else {
                console.log(res.msg);
            }
        } catch (error) {
            console.error("Error:", error);
        }
    }

    const addClient = async (formData) => {
        try {
            const res = await postContent(ACTIONS.addClient, formData);

            let alert;
            if (res.status === "ok") {
                alert = renderResponseAlert(res.msg, "success");
                $("#response-message").html(alert);
                $("#add-client-form")[0].reset(); 
            } else {
                alert = renderResponseAlert(res.msg, "danger");
                $("#response-message").html(alert);
            }
        } catch (error) {
            console.error("Error:", error);
            const alert = renderResponseAlert("Unrecognized error occoured", "success");
            $("#response-message").html(alert);
            $("#response-message").html(alert);
        }
    };

    const getClientsData = async () => {
        try {
            const res = await getContent(ACTIONS.getClientsData);
            if (res.status === "ok") {
                initializeClientsDataTable(res.data);
            } else {
                console.log(res.msg);
            }
        } catch (error) {
            console.error("Error:", error);
        }
    }

    const getPackagesData = async () => {
        try {
            const res = await getContent(ACTIONS.getPackagesData);
            if (res.status === "ok") {
                initializePackagesDataTable(res.data);
            } else {
                console.log(res.msg);
            }
        } catch (error) {
            console.error("Error:", error);
        }
    }

    const getContactPersonsData = async () => {
        try {
            const res = await getContent(ACTIONS.getContactPersonsData);
            if (res.status === "ok") {
                initializeContactPersonsDataTable(res.data);
            } else {
                console.log(res.msg);
            }
        } catch (error) {
            console.error("Error:", error);
        }
    }

    const getAccountManagersClients = async () => {
        try {
            const res = await getContent(ACTIONS.getAccountManagersClients);
            if (res.status === "ok") {
                initializeAccountManagersClientsDataTable(res.data);
            } else {
                console.log(res.msg);
            }
        } catch (error) {
            console.error("Error:", error);
        }
    }

    const initializePackagesDataTable = (data) => {
        const columns = [
            { title: "Name", data: "name" },
            { title: "Price", data: "price" },
            { title: "Currency", data: "currency" },
        ];

        $("#packagesTable").DataTable({ 
            data: data,
            columns: columns,
            order: [[0, "desc"]],
            deferRender: true,
            processing: true,
            language: {
              infoEmpty: "Empty clients data",
              emptyTable: "Empty clients data",
              lengthMenu: "<span>Show:</span> _MENU_",
              paginate: {
                first: "First",
                last: "Last",
                next: "&rarr;",
                previous: "&larr;",
              },
            },
            pageLength: 25,
            lengthMenu: [10, 25, 50, 75, 100],
            destroy: true,
            paging: true,
            info: false,
            searching: false,
        });

    };

    const initializeContactPersonsDataTable = (data) => {
        const columns = [
            { title: "First Name", data: "firstname" },
            { title: "Last Name", data: "lastname" },
            { title: "Email", data: "email" },
            { title: "Phone", data: "phone" },
            { title: "Client Name", data: "client_name" },
            { title: "Client Company", data: "client_company" },
        ];

        $("#contactPersonsTable").DataTable({
            data: data,
            columns: columns,
            order: [[0, "desc"]],
            deferRender: true,
            processing: true,
            language: {
              infoEmpty: "Empty clients data",
              emptyTable: "Empty clients data",
              lengthMenu: "<span>Show:</span> _MENU_",
              paginate: {
                first: "First",
                last: "Last",
                next: "&rarr;",
                previous: "&larr;",
              },
            },
            pageLength: 25,
            lengthMenu: [10, 25, 50, 75, 100],
            destroy: true,
            paging: true,
            info: false,
            searching: false,
        });
    }

    const initializeClientsDataTable = (data) => {
        const columns = [
            { title: "Name", data: "client_name" },
            { title: "Company Name", data: "company_name" },
            { title: "Country", data: "country" },
            { title: "VAT Number", data: "vat_number" },
            { title: "Currency", data: "currency" },
            { title: "Package", data: "package_name" },
            { 
                title: "Package Amount", 
                data: null,
                render: (data) => `${data.package_price} ${data.package_currency}` 
            },
            { 
                title: "Contacts", 
                data: null,
                render: (data) => `<button class="btn btn-primary btn-sm show-contacts" data-id="${data.id}">View Contacts</button>` 
            }
        ];
    
        $("#clientsTable").DataTable({
            data: data,
            columns: columns,
            order: [[0, "desc"]],
            deferRender: true,
            processing: true,
            language: {
              infoEmpty: "Empty clients data",
              emptyTable: "Empty clients data",
              lengthMenu: "<span>Show:</span> _MENU_",
              paginate: {
                first: "First",
                last: "Last",
                next: "&rarr;",
                previous: "&larr;",
              },
            },
            pageLength: 25,
            lengthMenu: [10, 25, 50, 75, 100],
            destroy: true,
            paging: true,
            info: false,
            searching: false,
        });        
    
        $("#clientsTable").on("click", ".show-contacts", function () {
            const clientId = $(this).data("id");
            const clientData = data.find(client => client.id === clientId);
    
            if (clientData) {
                showContactsModal(clientData);
            }
        });
    };

    const initializeAccountManagersClientsDataTable = (data) => {

        const columns = [
            { title: "Firstname", data: "employee_firstname" },
            { title: "Lastname", data: "employee_lastname" },
            { title: "Phone", data: "employee_phone" },
            { title: "Email", data: "employee_email" },
            { title: "Client Name", data: "client_name" },
            { title: "Client Company", data: "client_company" },


        ];
    
        $("#accManagersClientsTable").DataTable({
            data: data,
            columns: columns,
            order: [[0, "desc"]],
            deferRender: true,
            processing: true,
            language: {
              infoEmpty: "Empty clients data",
              emptyTable: "Empty clients data",
              lengthMenu: "<span>Show:</span> _MENU_",
              paginate: {
                first: "First",
                last: "Last",
                next: "&rarr;",
                previous: "&larr;",
              },
            },
            pageLength: 25,
            lengthMenu: [10, 25, 50, 75, 100],
            destroy: true,
            paging: true,
            info: false,
            searching: false,
        });

    };

    const showContactsModal = (clientData) => {
        let contactsHtml = "";
    
        if (clientData.contacts && clientData.contacts.length > 0) {
            clientData.contacts.forEach(contact => {
                contactsHtml += `
                    <tr>
                        <td>${contact.firstname}</td>
                        <td>${contact.lastname}</td>
                        <td>${contact.email}</td>
                        <td>${contact.phone}</td>
                    </tr>`;
            });
        } else {
            contactsHtml = `
                <tr>
                    <td colspan="4" class="text-center">No contact persons available</td>
                </tr>`;
        }
    
        const modalHtml = `
            <div class="modal fade" id="contactsModal" tabindex="-1" aria-labelledby="contactsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="contactsModalLabel">Contact Persons for ${clientData.client_name}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${contactsHtml}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>`;
    
        $("body").append(modalHtml);
    
        $("#contactsModal").modal("show");
    
        $("#contactsModal").on("hidden.bs.modal", function () {
            $(this).remove();
        });
    };
    
    
    

    const getClientFormFields = () => {
        const baseData = {
            name: $("#client_name").val(),
            company_name: $("#company_name").val(),
            country: $("#country").val(),
            vat_number: $("#vat_number").val(),
            currency: $("#currency").val(),
            package: $("#package").val(),
        };

        const contactPersons = [];
        $("#contact-persons-section .contact-person").each(function () {
            const firstname = $(this).find("input[name*='firstname']").val();
            const lastname = $(this).find("input[name*='lastname']").val();
            const email = $(this).find("input[name*='email']").val();
            const phone = $(this).find("input[name*='phone']").val();

            if (firstname && lastname && email && phone) {
                contactPersons.push({ firstname, lastname, email, phone });
            }
        });

        return {
            ...baseData,
            contactPersons, 
        };
    }
    
    $("#add-client-form").on("submit", async function (e) {
        e.preventDefault(); 
        const formData = getClientFormFields();
        await addClient(formData);
    });

    $(document).on('change', '#currency', function () {
        const currency = $(this).val();
        packagesForCurrency(currency);
    });

    getClientsData();
    getPackagesData();
    getContactPersonsData();
    getAccountManagersClients();
});