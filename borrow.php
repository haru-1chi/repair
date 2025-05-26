<?php
session_start();
require_once 'config/db.php';
require_once 'navbar.php';

if (isset($_POST['submit'])) {
    // รับข้อมูลจากฟอร์ม
    $device_ids = $_POST['device_id']; // This is an array
    $device_id_str = implode(", ", $device_ids); // Convert to "32, 15, 5"

    $borrowing_date = $_POST['borrowing_date'];
    $return_date = $_POST['return_date'];
    $borrower_name = $_POST['borrower_name'];
    $department_id = $_POST['department_id'];
    $phone_number = $_POST['phone_number'];
    $purpose = $_POST['purpose'];
    $device_status = 1;
    $status = 0;

    try {
        // เพิ่มข้อมูลการยืม
        $sql = "INSERT INTO equipmentborrow (device_id, borrowing_date, return_date, borrower_name, department_id, phone_number, purpose, status)
                VALUES (:device_id, :borrowing_date, :return_date, :borrower_name, :department_id, :phone_number, :purpose, :status)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':device_id', $device_id_str);
        $stmt->bindParam(':borrowing_date', $borrowing_date);
        $stmt->bindParam(':return_date', $return_date);
        $stmt->bindParam(':borrower_name', $borrower_name);
        $stmt->bindParam(':department_id', $department_id);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':purpose', $purpose);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            // อัปเดตสถานะอุปกรณ์ทั้งหมด
            $sqlUpdate = "UPDATE device SET device_status = :device_status WHERE id = :device_id";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            foreach ($device_ids as $id) {
                $stmtUpdate->bindParam(':device_status', $device_status);
                $stmtUpdate->bindParam(':device_id', $id);
                $stmtUpdate->execute();
            }

            $_SESSION['success'] = "ส่งคำขอยืมแล้ว ติดต่อรับที่ศูนย์คอมพิวเตอร์";
            header("refresh: 5; url=borrow.php");
        }
    } catch (PDOException $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="bootstrap/bootstrap/css/bootstrap.min.css">
    <link rel="shortcut icon" href="img/logo_20170112044011.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>ระบบยืมอุปกรณ์ออนไลน์ || ศูนย์คอมพิวเตอร์</title>
    <style>
        /* เพิ่มสไตล์ CSS เพื่อปรับแต่ง Navbar */
        .navbar {
            background-color: #365486;
            /* สีเขียว */
        }

        .navbar-brand {
            font-weight: 900;
            color: #fff !important;
            /* สีข้อความของ Navbar Brand */
        }

        .navbar-toggler-icon {
            background-color: #fff;
            /* สีไอคอน Toggle */
        }

        .navbar-nav .nav-link {
            color: #fff !important;
            transition: border 0.3s;
            /* เพิ่ม transition เพื่อทำให้การเปลี่ยนสีเป็นจุดประสงค์ */
        }

        .navbar-nav .nav-link:hover {
            border-bottom: 2px solid #ffc107;
            /* สีกรอบเมื่อ Hover */
            color: #ffc107 !important;
        }

        .btn.dropdown-toggle {
            border-radius: 0 !important;
            /* Remove rounded corners */
            border-color: transparent !important;
            /* Ensure no border by default */
        }

        .btn.dropdown-toggle:focus,
        .btn.dropdown-toggle:active,
        .show>.btn.dropdown-toggle {
            border: none;
            border-bottom: 2px solid #ffc107 !important;
            /* Change border color */
            color: #ffc107 !important;
            /* Change text color */
        }

        .btn.dropdown-toggle:not(:focus):not(:active) {
            border-color: transparent !important;
            /* Remove border when losing focus */
            box-shadow: none !important;
            /* Remove Bootstrap's default focus glow */
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php navbar(); ?>

    <div class="container mt-4" style="width: 50%;">
        <?php if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger" role="alert">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php } ?>

        <?php if (isset($_SESSION['warning'])) { ?>
            <div class="alert alert-warning" role="alert">
                <?php
                echo $_SESSION['warning'];
                unset($_SESSION['warning']);
                ?>
            </div>
        <?php } ?>

        <?php if (isset($_SESSION['success'])) { ?>
            <div class="alert alert-success" role="alert">
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </div>
        <?php } ?>
        <h1 class="text-center m-0">ยืมอุปกรณ์</h1>
        <div class="card card-body rounded-4 shadow-sm p-4 mt-4">

            <form action="" method="POST">

                <div class="row">
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label class="form-label">รายการอุปกรณ์</label>
                            <div id="device-select-container">
                                <div class="d-flex align-items-start">
                                    <div class="device-select mb-2">
                                        <select required name="device_id[]" class="form-select" aria-label="Default select example">
                                            <?php
                                            $sql = "SELECT * FROM device WHERE device_status NOT IN (1, 3, 4, 5)";
                                            $device = $conn->prepare($sql);
                                            $device->execute();
                                            $Alldevice = $device->fetchAll();
                                            ?>
                                            <option value="" disabled selected>เลือกรายการอุปกรณ์</option>
                                            <?php foreach ($Alldevice as $d) { ?>
                                                <option value="<?= $d['id'] ?>"><?= $d['device_name'] ?></option>
                                            <?php  } ?>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-warning ms-2" onclick="addDeviceSelect()">+</button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <script>
                        function addDeviceSelect() {
                            const container = document.getElementById('device-select-container');

                            // Create wrapper div for select + remove button
                            const wrapper = document.createElement('div');
                            wrapper.className = 'device-select mb-2 d-flex align-items-start';

                            // Clone the select element
                            const firstSelect = container.querySelector('select');
                            const newSelect = firstSelect.cloneNode(true);
                            newSelect.selectedIndex = 0;

                            // Create remove button
                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.className = 'btn btn-danger ms-2';
                            removeBtn.textContent = '-';
                            removeBtn.onclick = () => wrapper.remove();

                            // Add select and button to wrapper
                            wrapper.appendChild(newSelect);
                            wrapper.appendChild(removeBtn);

                            // Add wrapper to container
                            container.appendChild(wrapper);
                        }
                    </script>
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">วันที่ยืม</label>
                            <input required name="borrowing_date" type="date" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">วันส่งคืน</label>
                            <input required name="return_date" type="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">ชื่อ-สกุล ผู้ยืม</label>
                            <input required name="borrower_name" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label class="form-label" for="departInput">หน่วยงาน</label>
                            <input type="text" required class="form-control" id="departInput" name="department_id">
                            <input type="hidden" id="departId" name="depart_id">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">เบอร์มือถือ</label>
                            <input required name="phone_number" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">เหตุผล</label>
                            <textarea name="purpose" class="form-control" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" name="submit" class="btn btn-primary p-3">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
    <footer class="mt-5 footer mt-auto py-3" style="background: #fff;">
        <marquee style="font-weight: bold; font-size: 1rem"><span style="font-size: 1rem" class="text-muted text-center">Design website by นายอภิชน ประสาทศรี , พุฒิพงศ์ ใหญ่แก้ว &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Coding โดย นายอานุภาพ ศรเทียน &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ควบคุมโดย นนท์ บรรณวัฒน์ นักวิชาการคอมพิวเตอร์ ปฏิบัติการ</span>
        </marquee>
    </footer>
    <script src="bootstrap/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script>
        $(function() {
            function setupAutocomplete(type, inputId, hiddenInputId, url, addDataUrl, confirmMessage) {
                let inputChanged = false;

                $(inputId).autocomplete({
                        source: function(request, response) {
                            $.ajax({
                                url: url,
                                dataType: "json",
                                data: {
                                    term: request.term,
                                    type: type
                                },
                                success: function(data) {
                                    response(data); // Show suggestions
                                }
                            });
                        },
                        minLength: 1,
                        autoFocus: true,
                        select: function(event, ui) {
                            $(inputId).val(ui.item.label); // Fill input with label
                            $(hiddenInputId).val(ui.item.value); // Fill hidden input with ID
                            return false; // Prevent default behavior
                        }
                    })
                    .data("ui-autocomplete")._renderItem = function(ul, item) {
                        return $("<li>")
                            .append("<div>" + item.label + "</div>")
                            .appendTo(ul);
                    };

                $(inputId).on("autocompletefocus", function(event, ui) {
                    // You can log or do something here but won't change the input value
                    console.log("Item highlighted: ", ui.item.label);
                    return false;
                });

                $(inputId).on("keyup", function() {
                    inputChanged = true;
                });

                $(inputId).on("blur", function() {
                    if (inputChanged) {
                        const userInput = $(this).val().trim();
                        if (userInput === "") return;

                        let found = false;
                        $(this).autocomplete("instance").menu.element.find("div").each(function() {
                            if ($(this).text() === userInput) {
                                found = true;
                                return false;
                            }
                        });

                        if (!found) {
                            Swal.fire({
                                title: confirmMessage,
                                icon: "info",
                                showCancelButton: true,
                                confirmButtonText: "ใช่",
                                cancelButtonText: "ไม่"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: addDataUrl,
                                        method: "POST",
                                        data: {
                                            dataToInsert: userInput
                                        },
                                        success: function(response) {
                                            console.log("Data inserted successfully!");
                                            $(hiddenInputId).val(response); // Set inserted ID
                                        },
                                        error: function(xhr, status, error) {
                                            console.error("Error inserting data:", error);
                                        }
                                    });
                                } else {
                                    $(inputId).val(""); // Clear input
                                    $(hiddenInputId).val("");
                                }
                            });
                        }
                    }
                    inputChanged = false; // Reset the flag
                });
            }
            // Setup autocomplete for "หน่วยงาน" (departInput)
            setupAutocomplete(
                "depart",
                "#departInput",
                "#departId",
                "autocomplete.php",
                "insertDepart.php",
                "คุณต้องการเพิ่มข้อมูลนี้หรือไม่?"
            );
        });
    </script>
</body>

</html>