(function ($) {
    const form = $('form');
    const certRow = $('#certHere');
    const certBtn = $('#getCert');
    const retryBtn = $('#retry');
    const loader = $('#loader');
    const email = $('#email');
    const mobile = $('#phoneNumber');

    async function submitRes() {
        certBtn.css("display", "none");
        loader.append(`
            <div class="d-flex justify-content-center">
                <div class="spinner-border text-warning" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>`);

        const formData = new FormData();
        formData.append('email', email.val());
        formData.append('mobile', mobile.val());

        try {
            const res = await fetch('https://visvesmruti.tech/api/cert/', {
                method: 'POST',
                body: formData,
            });

            const result = await res.json();

            if (!res.ok) {
                reset();
                alert("Some Internet Issue, Please Try Again!");
            } else if (result.Code === 404) {
                reset();
                alert(result.Message);
            } else if (result.Code === 200) {
                const certCount = result.Data.length;
                if (certCount > 0) {
                    let rowCount = 0;
                    switch (certCount) {
                        case 1:
                            rowCount = 8;
                            break;
                        case 2:
                            rowCount = 5;
                            break;
                        case 3:
                            rowCount = 3;
                            break;
                        case 4:
                        case 5:
                        case 6:
                            rowCount = 2;
                            break;
                        default:
                            rowCount = 3;
                    }
                    for (let i = 0; i < certCount; i++) {
                        certRow.append(`
                            <div class="card col-lg-` + rowCount + ` col-md-2" style="margin: 30px; padding: 0 5px; display: flex;">
                                <div class="card-body">
                                    <h4 class="card-title text-dark">${result.Data[i].EventName}</h4>
                                    <br/>
                                    <h5 class="card-subtitle mb-2 text-dark">${result.Data[i].EventDept}</h5>
                                    <a href="${result.Data[i].URL}" class="card-link">Download</a>
                                </div>
                            </div>
                        `)
                    }
                    form.css("display", "none");
                    retryBtn.removeAttr("style");
                }
                alert(result.Message);
            }
        } catch (e) {
            reset();
            alert("Some Internet Issue, Please Try Again!")
        }
    }

    certBtn.on('click', function () {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        const re2 = /^[0][1-9]\d{9}$|^[1-9]\d{9}$/;
        if (String(email.val()).length < 1 || !re.test(String(email.val()).toLowerCase())) {
            alert("Please Enter Correct Email");
        } else if (String(mobile.val()).length < 1 || !re2.test(String(mobile.val()))) {
            alert("Please Enter Correct Mobile Number");
        } else {
            submitRes();
        }
    });

    retryBtn.on('click', function () {
        reset();
    });

    function reset() {
        loader.empty();
        certRow.empty();
        form.removeAttr("style");
        certBtn.css("display", "block");
        retryBtn.css("display", "none");
    }
})(jQuery);