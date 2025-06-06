@extends('layouts.user.main')
@section('content')
    <div class="container-xl">
        <div class="page-info m-auto">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white align-items-center">
                            <h3 id="department-name" class="mb-0">{{ $item->name }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <div class="card h-100 rounded border">
                                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Nhân viên thuộc phòng ban</h5>
                                            <div>
                                                <button class="btn btn-sm btn-light"
                                                        onclick="selectAll('department-employees')">Chọn tất cả
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="employee-list" id="department-employees"
                                                 ondragover="allowDrop(event)" ondrop="drop(event, 'department')">
                                                @foreach($employees as $employee)
                                                    <div class="employee-item" id="emp{{$employee->id}}"
                                                         data-emp-id="{{$employee->id}}"
                                                         onclick="toggleSelect(this)">
                                                        <input type="checkbox"
                                                               class="employee-checkbox form-check-input"
                                                               onclick="event.stopPropagation()">
                                                        <i class="fas fa-user me-2"></i> {{$employee->name}}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex justify-content-center align-items-center">
                                    <div class="arrow-container gap-4">
                                        <button class="btn btn-primary arrow-btn" onclick="moveSelected('right')">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="icon-tabler icons-tabler-outline icon-tabler-arrow-narrow-left"><path
                                                        stroke="none" d="M0 0h24v24H0z" fill="none"/><path
                                                        d="M5 12l14 0"/><path d="M5 12l4 4"/><path
                                                        d="M5 12l4 -4"/></svg>
                                        </span>
                                        </button>
                                        <button class="btn btn-secondary arrow-btn" onclick="moveSelected('left')">
                                         <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="icon-tabler icons-tabler-outline icon-tabler-arrow-narrow-right"><path
                                                        stroke="none" d="M0 0h24v24H0z" fill="none"/><path
                                                        d="M5 12l14 0"/><path d="M15 16l4 -4"/><path
                                                        d="M15 8l4 4"/></svg>
                                        </span>
                                        </button>
                                        <button class="btn btn-primary" onclick="saveDepartmentChanges()">Lưu thay đổi
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-5 mb-3">
                                    <div class="card h-100 rounded border">
                                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Nhân viên không thuộc phòng ban</h5>
                                            <div>
                                                <button class="btn btn-sm btn-light"
                                                        onclick="selectAll('non-department-employees')">Chọn tất cả
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="employee-list" id="non-department-employees"
                                                 ondragover="allowDrop(event)" ondrop="drop(event, 'non-department')">
                                                @foreach($notEmployees as $notEmployee)
                                                    <div class="employee-item" id="emp{{$notEmployee->id}}"
                                                         data-emp-id="{{$notEmployee->id}}"
                                                         onclick="toggleSelect(this)">
                                                        <input type="checkbox"
                                                               class="employee-checkbox form-check-input"
                                                               onclick="event.stopPropagation()">
                                                        <i class="fas fa-user me-2"></i> {{$notEmployee->name}}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Khởi tạo drag and drop cho các employee items
        document.querySelectorAll('.employee-item').forEach(item => {
            item.setAttribute('draggable', 'true');
            item.addEventListener('dragstart', function (event) {
                drag(event);
            });
        });

        // Thêm class hiệu ứng khi kéo thả
        const employeeLists = document.querySelectorAll('.employee-list');
        employeeLists.forEach(list => {
            list.addEventListener('dragenter', function (e) {
                this.classList.add('dragover');
            });

            list.addEventListener('dragleave', function (e) {
                this.classList.remove('dragover');
            });

            list.addEventListener('dragend', function (e) {
                this.classList.remove('dragover');
            });
        });

        // Hàm toggle chọn nhân viên
        function toggleSelect(element) {
            const checkbox = element.querySelector('.employee-checkbox');
            checkbox.checked = !checkbox.checked;
            element.classList.toggle('selected');
        }

        // Hàm chọn tất cả nhân viên trong một danh sách
        function selectAll(listId) {
            const list = document.getElementById(listId);
            const employees = list.querySelectorAll('.employee-item');
            const allChecked = Array.from(employees).every(emp =>
                emp.querySelector('.employee-checkbox').checked
            );

            employees.forEach(emp => {
                const checkbox = emp.querySelector('.employee-checkbox');
                checkbox.checked = !allChecked;
                emp.classList.toggle('selected', !allChecked);
            });
        }

        // Hàm xử lý kéo
        function drag(ev) {
            ev.dataTransfer.setData("text", ev.target.id);
            ev.target.classList.add('dragging');
        }

        // Hàm cho phép thả
        function allowDrop(ev) {
            ev.preventDefault();
        }

        // Hàm xử lý thả
        function drop(ev, targetType) {
            ev.preventDefault();
            const data = ev.dataTransfer.getData("text");
            const draggedElement = document.getElementById(data);

            // Xóa class hiệu ứng
            draggedElement.classList.remove('dragging');
            document.querySelectorAll('.employee-list').forEach(list => {
                list.classList.remove('dragover');
            });

            // Đặt phần tử vào container đích
            if (targetType === 'department') {
                document.getElementById('department-employees').appendChild(draggedElement);
            } else {
                document.getElementById('non-department-employees').appendChild(draggedElement);
            }

            // Bỏ chọn phần tử
            const checkbox = draggedElement.querySelector('.employee-checkbox');
            checkbox.checked = false;
            draggedElement.classList.remove('selected');
        }

        // Di chuyển nhân viên đã chọn qua lại
        function moveSelected(direction) {
            const selectedEmployees = document.querySelectorAll('.employee-checkbox:checked');

            if (selectedEmployees.length === 0) {
                showToast('error', 'Vui lòng chọn ít nhất một tài khoản');
                return;
            }

            selectedEmployees.forEach(checkbox => {
                const empItem = checkbox.closest('.employee-item');
                checkbox.checked = false;
                empItem.classList.remove('selected');

                if (direction === 'right') {
                    document.getElementById('department-employees').appendChild(empItem);
                } else {
                    document.getElementById('non-department-employees').appendChild(empItem);
                }
            });
        }

        // Lưu thay đổi
        async function saveDepartmentChanges() {
            const departmentEmployeeIds = [];
            document.querySelectorAll('#department-employees .employee-item').forEach(emp => {
                departmentEmployeeIds.push(emp.dataset.empId);
            });

            const nonDepartmentEmployeeIds = [];
            document.querySelectorAll('#non-department-employees .employee-item').forEach(emp => {
                nonDepartmentEmployeeIds.push(emp.dataset.empId);
            });


            try {
                const response = await fetch('{{route('departments.employees.post', $item->id)}}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ids: departmentEmployeeIds})
                });

                if (response.status === 200) {
                    showToast('success', 'Cập nhật phòng ban|dự án thành công');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1100);
                } else {
                    showToast('danger', 'Cập nhật phòng ban|dự án thất bại');
                }
            } catch (error) {
                showToast('danger', 'Có lỗi xảy ra khi cập nhật phòng ban|dự án');
            }
        }
    </script>
@endsection

@section('css')
    <style>
        .page-info {
            .card {
                min-height: 400px;
            }

            .employee-item {
                cursor: pointer;
                margin-bottom: 10px;
                transition: all 0.2s;
                padding: 10px;
                border-radius: 4px;
                border: 1px solid #dee2e6;
            }

            .employee-item:hover {
                background-color: #f8f9fa;
            }

            .employee-list {
                min-height: 300px;
                padding: 15px;
                border-radius: 5px;
                background-color: #f8f9fa;
            }

            .dragover {
                background-color: #e2f0ff;
                border: 2px dashed #0d6efd;
            }

            .dragging {
                opacity: 0.5;
            }

            .arrow-container {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 20px 0;
            }

            .arrow-btn {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .employee-checkbox {
                margin-right: 10px;
            }

            .selected {
                background-color: #cfe2ff;
                border: 1px solid #9ec5fe;
            }
        }
    </style>
@endsection

