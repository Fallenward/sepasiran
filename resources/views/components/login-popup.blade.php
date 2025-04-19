<!-- Modal -->
<div class="modal fade" id="loginRegisterModal" tabindex="-1" aria-labelledby="loginRegisterModalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                <h2 class="modal-title fs-5" id="loginRegisterModalModalLabel">ورود یا ثبت نام</h2>
            </div>
            <div class="modal-body login-apex-popup">
                <img src="{{$settings->footer_setting_logo}}" class="img-fluid w-50">
                <h2 class="my-3">ورود به اپکس</h2>
                <x-line-divider-single></x-line-divider-single>

                <!-- Phone Number Input -->
                <div class="form-group col-12 my-3" data-mdb-input-init>
                    <input type="text" class="form-control text-center" id="phone" placeholder="شماره موبایل" maxlength="11">
                </div>

                <!-- Verification Code Input (Initially Hidden) -->
                <div class="form-group col-12 my-3" id="verificationCodeGroup" style="display: none;" dir="ltr">
                    <div class="d-flex justify-content-between">
                        <!-- Verification code inputs (left to right) -->
                        <input type="text" class="form-control text-center verification-code-input" maxlength="1" oninput="moveToNext(this, 1)" onkeydown="moveToPrevious(event, this, 1)" autofocus>
                        <input type="text" class="form-control text-center verification-code-input" maxlength="1" oninput="moveToNext(this, 2)" onkeydown="moveToPrevious(event, this, 2)">
                        <input type="text" class="form-control text-center verification-code-input" maxlength="1" oninput="moveToNext(this, 3)" onkeydown="moveToPrevious(event, this, 3)">
                        <input type="text" class="form-control text-center verification-code-input" maxlength="1" oninput="moveToNext(this, 4)" onkeydown="moveToPrevious(event, this, 4)">
                        <input type="text" class="form-control text-center verification-code-input" maxlength="1" oninput="moveToNext(this, 5)" onkeydown="moveToPrevious(event, this, 5)">
                    </div>
                </div>

                <!-- User Info Fields (Initially Hidden) -->
                <div class="form-group col-12 my-3" id="userInfoGroup" style="display: none;">

                    <input type="text" class="form-control text-center my-2" id="name" placeholder="نام">

                    <input type="text" class="form-control text-center my-2" id="family" placeholder="نام خانوادگی">
                    <!-- Province Select Box -->
                    <select class="form-control text-center my-2" id="province_id">
                        <option value="">انتخاب استان</option>
                        <!-- Options will be populated dynamically -->
                    </select>

                    <!-- City Select Box -->
                    <select class="form-control text-center my-2" id="city_id">
                        <option value="">انتخاب شهر</option>
                        <!-- Options will be populated dynamically -->
                    </select>

                    <!-- Level ID Select Box -->
                    <select class="form-control text-center my-2" id="level_id">
                        <option value="">انتخاب پایه تحصیلی</option>
                        <!-- Options will be populated dynamically -->
                    </select>

                    <!-- Field ID Select Box -->
                    <select class="form-control text-center my-2" id="field_id">
                        <option value="">انتخاب رشته تحصیلی</option>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
            </div>
            <div class="modal-footer-custom">
                <!-- Buttons -->
                <div class="col-12 gap-2 d-flex justify-content-center">
                    <button type="button" id="submitButton" class="btn btn-primary w-100" disabled>ارسال کد</button>
                    <button type="button" id="verifyCodeButton" class="btn btn-primary w-50" style="display: none;">تایید کد</button>
                    <button type="button" id="editPhoneButton" class="btn btn-secondary w-50" style="display: none;">ویرایش شماره</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- JavaScript for real-time validation and AJAX requests -->
<script defer>
    // Function to move focus to the next input field
    function moveToNext(input, nextIndex) {
        if (input.value.length === 1) {
            const nextInput = document.querySelector(`.verification-code-input:nth-child(${nextIndex + 1})`);
            if (nextInput) {
                nextInput.focus();
            }
            if (nextIndex === 5) {
                const verifyCodeButton = document.getElementById('verifyCodeButton');
                if (verifyCodeButton) {
                    verifyCodeButton.click();
                }
            }
        }
    }

    // Function to move focus to the previous input field on Backspace
    function moveToPrevious(event, input, currentIndex) {
        if (event.key === 'Backspace' && input.value.length === 0) {
            const previousInput = document.querySelector(`.verification-code-input:nth-child(${currentIndex - 1})`);
            if (previousInput) {
                previousInput.focus();
            }
        }
    }

    // Phone number input validation
    document.getElementById('phone').addEventListener('input', function (e) {
        this.value = this.value.replace(/\D/g, '').slice(0, 11);
        const submitButton = document.getElementById('submitButton');
        submitButton.disabled = this.value.length !== 11;
    });

    // Handle "Send Code" button click
    document.getElementById('submitButton').addEventListener('click', function () {
        const phoneNumber = document.getElementById('phone').value;
        const submitButton = document.getElementById('submitButton');
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...';
        fetch("{{ route('frontend.send.verification.code') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ phone: phoneNumber })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const verificationCodeGroup = document.getElementById('verificationCodeGroup');
                    verificationCodeGroup.classList.add('show');
                    verificationCodeGroup.style.display = 'block';
                    const firstVerificationInput = document.querySelector('.verification-code-input');
                    if (firstVerificationInput) {
                        firstVerificationInput.focus(); // Ensure the first input is focused
                    }
                    const verifyCodeButton = document.getElementById('verifyCodeButton');
                    verifyCodeButton.classList.add('show');
                    verifyCodeButton.style.display = 'block';
                    const editPhoneButton = document.getElementById('editPhoneButton');
                    editPhoneButton.classList.add('show');
                    editPhoneButton.style.display = 'block';
                    submitButton.style.display = 'none';
                    document.getElementById('phone').readOnly = true;
                    alert(`کد اعتبارسنجی شما: ${data.code}`);
                    showMessage('کد اعتبارسنجی ارسال شد.', 'success');
                } else {
                    showMessage('خطا در ارسال کد اعتبارسنجی.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('خطا در ارتباط با سرور.', 'error');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = 'ارسال کد';
            });
    });

    // Handle "Edit Phone" button click
    document.getElementById('editPhoneButton').addEventListener('click', function () {
        const verificationCodeGroup = document.getElementById('verificationCodeGroup');
        const verifyCodeButton = document.getElementById('verifyCodeButton');
        const editPhoneButton = document.getElementById('editPhoneButton');
        const userInfoGroup = document.getElementById('userInfoGroup');
        verificationCodeGroup.classList.remove('show');
        verifyCodeButton.classList.remove('show');
        editPhoneButton.classList.remove('show');
        userInfoGroup.classList.remove('show');
        verificationCodeGroup.style.display = 'none';
        verifyCodeButton.style.display = 'none';
        editPhoneButton.style.display = 'none';
        userInfoGroup.style.display = 'none';
        document.getElementById('phone').value = '';
        document.getElementById('phone').readOnly = false;
        document.querySelectorAll('.verification-code-input').forEach(input => input.value = '');
        document.getElementById('submitButton').style.display = 'block';
        document.getElementById('submitButton').disabled = true;
        showMessage('لطفاً شماره تلفن خود را وارد کنید.', 'info');
    });

    // Handle "Verify Code" button click
    document.getElementById('verifyCodeButton').addEventListener('click', function () {
        const phoneNumber = document.getElementById('phone').value;
        const verificationCode = Array.from(document.querySelectorAll('.verification-code-input')).map(input => input.value).join('');
        const verifyCodeButton = document.getElementById('verifyCodeButton');
        verifyCodeButton.disabled = true;
        verifyCodeButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال تایید...';

        fetch("{{ route('frontend.verify.code') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ phone: phoneNumber, code: verificationCode })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Data received:', data);
                    const verificationCodeInputs = document.querySelectorAll('.verification-code-input');
                    verificationCodeInputs.forEach(input => {
                        input.readOnly = true;
                        input.style.backgroundColor = '#f0f0f0';
                    });

                    if (data.registered === false) {
                        // نمایش فرم اطلاعات کاربر
                        const userInfoGroup = document.getElementById('userInfoGroup');
                        userInfoGroup.style.display = 'block';
                        fetchProvinceOptions();
                        verifyCodeButton.innerHTML = 'ثبت اطلاعات';
                        verifyCodeButton.disabled = false;
                        verifyCodeButton.onclick = function () {
                            submitUserInfo(phoneNumber, verificationCode);
                        };
                        showMessage('کد اعتبارسنجی تایید شد! لطفاً اطلاعات خود را وارد کنید.', 'success');
                    } else {
                        // هدایت کاربر به صفحه اصلی پس از ورود
                        showMessage('ورود موفقیت‌آمیز! در حال هدایت...', 'success');
                        setTimeout(() => {
                            window.location.href = "{{ route('frontend.home') }}";
                        }, 1000);
                    }
                } else {
                    showMessage('کد اعتبارسنجی نامعتبر است.', 'error');
                    verifyCodeButton.disabled = false;
                    verifyCodeButton.innerHTML = 'تایید کد';
                }

            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('خطا در ارتباط با سرور.', 'error');
                verifyCodeButton.disabled = false;
                verifyCodeButton.innerHTML = 'تایید کد';
            });
    });

    // Function to fetch province options
    function fetchProvinceOptions() {
        fetch("{{ route('frontend.get.provinces') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const provinceSelect = document.getElementById('province_id');
                    provinceSelect.innerHTML = '<option value="">انتخاب استان</option>';
                    data.provinces.forEach(province => {
                        provinceSelect.innerHTML += `<option value="${province.id}">${province.name}</option>`;
                    });
                    provinceSelect.addEventListener('change', function () {
                        const provinceId = this.value;
                        if (provinceId) {
                            fetchCityOptions(provinceId);
                        } else {
                            const citySelect = document.getElementById('city_id');
                            citySelect.innerHTML = '<option value="">انتخاب شهر</option>';
                        }
                    });
                    fetchLevelAndFieldOptions();
                } else {
                    showMessage('خطا در دریافت اطلاعات استان.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('خطا در ارتباط با سرور.', 'error');
            });
    }

    // Function to fetch city options based on selected province
    function fetchCityOptions(provinceId) {
        fetch(`{{ route('frontend.get.cities') }}?province_id=${provinceId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const citySelect = document.getElementById('city_id');
                    citySelect.innerHTML = '<option value="">انتخاب شهر</option>';
                    data.cities.forEach(city => {
                        citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                    });
                } else {
                    showMessage('خطا در دریافت اطلاعات شهر.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('خطا در ارتباط با سرور.', 'error');
            });
    }

    // Function to fetch level and field options
    function fetchLevelAndFieldOptions() {
        fetch("{{ route('frontend.get.level.and.field.options') }}", {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const levelSelect = document.getElementById('level_id');
                    levelSelect.innerHTML = '<option value="">انتخاب پایه تحصیلی</option>';
                    data.levels.forEach(level => {
                        levelSelect.innerHTML += `<option value="${level.id}">${level.title}</option>`;
                    });
                    const fieldSelect = document.getElementById('field_id');
                    fieldSelect.innerHTML = '<option value="">انتخاب رشته تحصیلی</option>';
                    data.fields.forEach(field => {
                        fieldSelect.innerHTML += `<option value="${field.id}">${field.title}</option>`;
                    });
                } else {
                    showMessage('خطا در دریافت اطلاعات پایه و رشته تحصیلی.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('خطا در ارتباط با سرور.', 'error');
            });
    }

    // Function to submit user info
    function submitUserInfo(phoneNumber, verificationCode) {
        const name = document.getElementById('name').value;
        const family = document.getElementById('family').value;
        const province_id = document.getElementById('province_id').value;
        const city_id = document.getElementById('city_id').value;
        const level_id = document.getElementById('level_id').value;
        const field_id = document.getElementById('field_id').value;
        const verifyCodeButton = document.getElementById('verifyCodeButton');
        verifyCodeButton.disabled = true;
        verifyCodeButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ثبت...';
        fetch("{{ route('frontend.submit.user.info') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                phone: phoneNumber,
                code: verificationCode,
                name: name,
                family: family,
                province_id: province_id,
                city_id: city_id,
                level_id: level_id,
                field_id: field_id
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('اطلاعات با موفقیت ثبت شد!', 'success');
                } else {
                    showMessage('خطا در ثبت اطلاعات.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('خطا در ارتباط با سرور.', 'error');
            })
            .finally(() => {
                verifyCodeButton.disabled = false;
                verifyCodeButton.innerHTML = 'ثبت اطلاعات';
            });
    }

    // Function to show messages with animations
    function showMessage(message, type) {
        const messageElement = document.createElement('div');
        messageElement.className = `error-message ${type}-message show`;
        messageElement.textContent = message;
        const modalBody = document.querySelector('.modal-body');
        modalBody.appendChild(messageElement);
        setTimeout(() => {
            messageElement.classList.remove('show');
            setTimeout(() => {
                messageElement.remove();
            }, 300);
        }, 3000);
    }
</script>

