/* =====================================================================
   Aplikasi Presensi Siswa - SMK TI Airlangga Samarinda
   Interaksi halaman: password, konfirmasi, pratinjau foto, jam, simulasi tap.
   ===================================================================== */

(function () {
    "use strict";

    function onReady(callback) {
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", callback);
        } else {
            callback();
        }
    }

    function initials(name) {
        var parts = String(name || "").trim().split(/\s+/).filter(Boolean);

        if (parts.length === 0) {
            return "?";
        }

        if (parts.length === 1) {
            return parts[0].substring(0, 2).toUpperCase();
        }

        return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase();
    }

    function initPasswordToggles() {
        document.querySelectorAll("[data-toggle-password]").forEach(function (button) {
            button.addEventListener("click", function () {
                var selector = button.getAttribute("data-toggle-password");
                var input = selector ? document.querySelector(selector) : null;

                if (!input) {
                    return;
                }

                var show = input.type === "password";
                input.type = show ? "text" : "password";

                var icon = button.querySelector("i");
                if (icon) {
                    icon.className = show ? "bi bi-eye-slash" : "bi bi-eye";
                }

                button.setAttribute("aria-label", show ? "Sembunyikan password" : "Tampilkan password");
                input.focus();
            });
        });
    }

    function initConfirmForms() {
        document.querySelectorAll("form[data-confirm]").forEach(function (form) {
            form.addEventListener("submit", function (event) {
                var message = form.getAttribute("data-confirm") || "Lanjutkan tindakan ini?";
                if (!window.confirm(message)) {
                    event.preventDefault();
                }
            });
        });
    }

    function initPhotoPreview() {
        var input = document.getElementById("fotoInput");
        var preview = document.getElementById("photoPreview");
        var errorBox = document.getElementById("fotoError");

        if (!input || !preview) {
            return;
        }

        input.addEventListener("change", function () {
            if (errorBox) {
                errorBox.textContent = "";
            }

            input.classList.remove("is-invalid");

            var file = input.files && input.files[0];
            if (!file) {
                return;
            }

            var allowed = ["image/jpeg", "image/png"];
            if (allowed.indexOf(file.type) === -1) {
                if (errorBox) {
                    errorBox.textContent = "Foto harus berformat JPG, JPEG, atau PNG.";
                }
                input.value = "";
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                if (errorBox) {
                    errorBox.textContent = "Ukuran foto maksimal 2 MB.";
                }
                input.value = "";
                return;
            }

            var reader = new FileReader();
            reader.onload = function (event) {
                var image = document.createElement("img");
                image.src = String(event.target.result);
                image.alt = "Pratinjau foto siswa";

                preview.innerHTML = "";
                preview.appendChild(image);
            };
            reader.readAsDataURL(file);
        });
    }

    function initLiveClock() {
        var clock = document.getElementById("liveClock");
        var dateElement = document.getElementById("liveDate");

        if (!clock && !dateElement) {
            return;
        }

        var timeFormatter;
        var dateFormatter;

        try {
            timeFormatter = new Intl.DateTimeFormat("id-ID", {
                timeZone: "Asia/Makassar",
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit",
                hour12: false
            });
            dateFormatter = new Intl.DateTimeFormat("id-ID", {
                timeZone: "Asia/Makassar",
                weekday: "long",
                day: "numeric",
                month: "long",
                year: "numeric"
            });
        } catch (error) {
            return;
        }

        function update() {
            var now = new Date();

            if (clock) {
                clock.textContent = timeFormatter.format(now).replace(/\./g, ":") + " WITA";
            }

            if (dateElement) {
                dateElement.textContent = dateFormatter.format(now);
            }
        }

        update();
        window.setInterval(update, 1000);
    }

    function initTapSimulation() {
        var form = document.getElementById("tapForm");
        if (!form) {
            return;
        }

        var input = document.getElementById("nisnInput");
        var resultBox = document.getElementById("tapResult");
        var errorBox = document.getElementById("tapError");
        var submitButton = document.getElementById("tapSubmit");
        var template = document.getElementById("tapResultTemplate");

        function setLoading(loading) {
            if (!submitButton) {
                return;
            }

            submitButton.disabled = loading;

            var label = submitButton.querySelector(".tap-submit-label");
            var loadingLabel = submitButton.querySelector(".tap-submit-loading");

            if (label) {
                label.classList.toggle("d-none", loading);
            }

            if (loadingLabel) {
                loadingLabel.classList.toggle("d-none", !loading);
            }
        }

        function showError(message) {
            if (!errorBox) {
                return;
            }

            errorBox.textContent = message;
            errorBox.hidden = false;
        }

        function clearError() {
            if (!errorBox) {
                return;
            }

            errorBox.textContent = "";
            errorBox.hidden = true;
        }

        function statusVariant(status) {
            if (status === "success") {
                return "success";
            }

            if (status === "duplicate") {
                return "warning";
            }

            return "danger";
        }

        function statusIcon(status) {
            switch (status) {
                case "success":
                    return "bi-check-circle-fill";
                case "duplicate":
                    return "bi-clock-history";
                case "not_found":
                    return "bi-person-x-fill";
                case "invalid":
                    return "bi-exclamation-circle-fill";
                default:
                    return "bi-exclamation-octagon-fill";
            }
        }

        function statusTitle(status) {
            switch (status) {
                case "success":
                    return "Presensi berhasil";
                case "duplicate":
                    return "Presensi sudah tercatat";
                case "not_found":
                    return "Siswa tidak ditemukan";
                case "invalid":
                    return "NISN tidak valid";
                default:
                    return "Terjadi kesalahan";
            }
        }

        function setField(root, field, value) {
            var element = root.querySelector('[data-field="' + field + '"]');
            if (element) {
                element.textContent = value;
            }
        }

        function renderResult(data) {
            if (!resultBox || !template) {
                return;
            }

            var status = data.status || "error";
            var fragment = template.content.cloneNode(true);
            var node = fragment.querySelector(".tap-result");

            if (!node) {
                return;
            }

            node.classList.add("tap-result--" + statusVariant(status));

            var icon = node.querySelector(".tap-result-icon i");
            if (icon) {
                icon.className = "bi " + statusIcon(status);
            }

            var title = node.querySelector(".tap-result-title");
            if (title) {
                title.textContent = statusTitle(status);
            }

            var message = node.querySelector(".tap-result-message");
            if (message) {
                message.textContent = data.message || "";
            }

            var person = node.querySelector(".tap-result-person");
            var siswa = data.siswa;

            if (person && siswa && (status === "success" || status === "duplicate")) {
                person.classList.remove("d-none");

                var avatar = person.querySelector('[data-field="avatar"]');
                if (avatar) {
                    avatar.innerHTML = "";

                    if (siswa.foto_url) {
                        avatar.classList.remove("avatar-initials");
                        var image = document.createElement("img");
                        image.src = siswa.foto_url;
                        image.alt = "Foto " + (siswa.nama || "siswa");
                        avatar.appendChild(image);
                    } else {
                        avatar.classList.add("avatar-initials");
                        avatar.textContent = initials(siswa.nama);
                    }
                }

                setField(person, "nama", siswa.nama || "");
                setField(person, "meta", [siswa.nisn, siswa.nama_kelas].filter(Boolean).join(" \u00B7 "));

                var timeParts = [];
                if (data.waktu_teks) {
                    timeParts.push(status === "duplicate" ? "Tercatat" : "Dicatat");
                    if (data.tanggal_teks) {
                        timeParts.push("pada " + data.tanggal_teks);
                    }
                    timeParts.push("\u00B7 " + data.waktu_teks);
                }
                setField(person, "waktu", timeParts.join(" "));
            }

            resultBox.innerHTML = "";
            resultBox.appendChild(node);
        }

        function addHistory(data) {
            var body = document.getElementById("tapHistoryBody");
            if (!body || !data.siswa) {
                return;
            }

            var emptyRow = document.getElementById("tapHistoryEmpty");
            if (emptyRow) {
                emptyRow.remove();
            }

            var row = document.createElement("tr");

            var studentCell = document.createElement("td");
            var person = document.createElement("div");
            person.className = "person-cell";

            var avatar = document.createElement("span");
            avatar.className = "avatar avatar-sm";

            if (data.siswa.foto_url) {
                var image = document.createElement("img");
                image.src = data.siswa.foto_url;
                image.alt = "Foto " + (data.siswa.nama || "siswa");
                avatar.appendChild(image);
            } else {
                avatar.classList.add("avatar-initials");
                avatar.textContent = initials(data.siswa.nama);
            }

            var info = document.createElement("div");
            info.className = "min-w-0";

            var name = document.createElement("div");
            name.className = "person-name text-truncate";
            name.textContent = data.siswa.nama || "";

            var meta = document.createElement("div");
            meta.className = "person-meta";
            meta.textContent = data.siswa.nama_kelas || "";

            info.appendChild(name);
            info.appendChild(meta);
            person.appendChild(avatar);
            person.appendChild(info);
            studentCell.appendChild(person);

            var timeCell = document.createElement("td");
            timeCell.className = "text-end";

            var time = document.createElement("span");
            time.className = "fw-semibold small";
            time.textContent = data.waktu_teks || "";
            timeCell.appendChild(time);

            row.appendChild(studentCell);
            row.appendChild(timeCell);

            body.insertBefore(row, body.firstElementChild);

            while (body.children.length > 8) {
                body.removeChild(body.lastElementChild);
            }
        }

        if (input) {
            input.addEventListener("input", function () {
                input.value = input.value.replace(/\D/g, "").slice(0, 10);
                input.classList.remove("is-invalid");
                clearError();
            });
        }

        form.addEventListener("submit", function (event) {
            event.preventDefault();

            if (!input) {
                return;
            }

            var nisn = input.value.trim();

            if (!/^\d{10}$/.test(nisn)) {
                showError("NISN harus terdiri dari tepat 10 digit angka.");
                input.classList.add("is-invalid");
                input.focus();
                return;
            }

            clearError();
            setLoading(true);

            var formData = new FormData(form);

            fetch(form.action, {
                method: "POST",
                body: formData,
                credentials: "same-origin",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json"
                }
            })
                .then(function (response) {
                    return response.json()
                        .catch(function () {
                            return null;
                        })
                        .then(function (data) {
                            if (!data) {
                                return {
                                    status: "error",
                                    message: "Respons server tidak valid (HTTP " + response.status + ")."
                                };
                            }

                            return data;
                        });
                })
                .then(function (data) {
                    renderResult(data);

                    if (data.status === "success") {
                        addHistory(data);
                    }

                    form.reset();

                    if (input) {
                        input.focus();
                    }
                })
                .catch(function () {
                    renderResult({
                        status: "error",
                        message: "Tidak dapat menghubungi server. Periksa koneksi lalu coba lagi."
                    });
                })
                .then(function () {
                    setLoading(false);
                    if (input) {
                        input.focus();
                    }
                });
        });

        if (input) {
            input.focus();
        }
    }

    onReady(function () {
        initPasswordToggles();
        initConfirmForms();
        initPhotoPreview();
        initLiveClock();
        initTapSimulation();
    });
})();
