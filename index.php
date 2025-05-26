<?php
require_once 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/logo_20170112044011.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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

        /*----------------------------------------- */
        .box1 {
            text-decoration: none;
            width: 25.0625rem;
            height: 25.0625rem;
            flex-shrink: 0;
            border-radius: 4.6875rem;
            background: #DCF2F1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            /* ตั้งค่า overflow เป็น hidden เพื่อให้ border กินเข้ามาด้านในของ box */
            position: relative;
        }

        .box1 h1 {
            color: #365486;
        }

        .box1:hover {
            scale: 1.1;
            border-radius: 4.6875rem;
            border: 17px solid #7FC7D9;
            background: #DCF2F1;
            transition: ease-in-out .2s;
        }
    </style>
</head>

<body>
    <nav class="navbar p-3 navbar-expand-lg bg-green text-center">
        <div class="container">
            <a class="navbar-brand" href="../orderit/dashboard.php">ระบบบริหารงานซ่อม</a>
            <div class="collapse navbar-collapse align-items-center" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="borrow.php">ยืม</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">สำหรับเจ้าหน้าที่</a>
                    </li>
                    <li class="nav-item ms-5">
                        <a class="nav-link" href="system/logout.php" type="submit">ออกจากระบบ</a>
                    </li>

                </ul>

            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row">
            <!-- <div class="col-8">
                <div class="imageTitle">
                    <img src="img/24242.png" alt="">
                </div>
            </div> -->
            <!-- <div class="col-12">
                <div class="box">
                    <div class="content">
                        <br>
                        <br>
                        <h1 class="text-center">ระบบยืมอุปกรณ์ออนไลน์</h1>
                        <p class="text-center">สร้างเมื่อ 14 พฤศจิกายน 2566</p>
                        <div class="d-flex justify-content-center">
                            <div class="d-grid buttonSpace te gap-3">
                                <a href="borrow.php" class="btN-Green">ยืม</a>
                                <a href="admin.php" class="btN-Red">สำหรับเจ้าหน้าที่</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div> -->

            <!-- <div class="col mb-5">
                <a href="../OrderIT" class="box1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="200" height="178" viewBox="0 0 200 178" fill="none">
                        <path d="M75.7531 147.27C74.7288 145.215 72.9441 145.006 72.2219 145.006C71.4997 145.006 69.715 145.211 68.7428 147.156L66.0796 152.482C63.8678 156.919 57.3886 156.43 55.8435 151.756L49.9998 134.232L46.3158 145.301C44.2706 151.433 38.545 155.555 32.0797 155.555H27.7777C24.7082 155.555 22.2221 153.069 22.2221 149.999C22.2221 146.93 24.7082 144.444 27.7777 144.444H32.0797C33.7568 144.444 35.2429 143.374 35.7742 141.784L42.0901 122.812C43.2359 119.406 46.4095 117.114 49.9998 117.114C53.59 117.114 56.7636 119.402 57.906 122.812L62.7254 137.27C69.583 131.649 81.4962 133.902 85.642 142.187C86.2982 143.499 87.5482 144.253 88.892 144.36V115.847L133.336 71.7184V55.5553H86.1107C81.5274 55.5553 77.7774 51.8053 77.7774 47.222V0H8.3333C3.71526 0 0 3.71526 0 8.3333V169.444C0 174.062 3.71526 177.777 8.3333 177.777H124.999C129.617 177.777 133.333 174.062 133.333 169.444V155.555L88.8885 155.517C83.2913 155.409 78.2705 152.294 75.7531 147.27ZM133.333 42.3262C133.333 40.1387 132.465 38.0207 130.902 36.4582L96.9093 2.43055C95.3468 0.868052 93.2288 0 91.0066 0H88.8885V44.4443H133.333V42.3262ZM99.9996 120.472V144.444H123.954L180.093 87.9232L156.524 64.3539L99.9996 120.472ZM197.409 58.1004L186.343 47.0345C182.892 43.5831 177.291 43.5831 173.84 47.0345L164.378 56.4963L187.947 80.0656L197.409 70.6039C200.864 67.1525 200.864 61.5518 197.409 58.1004Z" fill="#365486" />
                    </svg>
                </a>
                <br>
                <h1 class="text-center ">ระบบบริหารงานซ่อม</h1>
            </div> -->
            <h1 class="text-center">ระบบยืมอุปกรณ์ออนไลน์</h1>
            <p class="text-center">สร้างเมื่อ 14 พฤศจิกายน 2566</p>
            <div class="d-flex justify-content-center">
                <div class="mb-5 ms-auto me-auto">
                    <a href="borrow.php" class="box1">
                        <svg fill="#365486" width="225px" height="225px" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">

                            <g data-name="16 hand delivery box" id="_16_hand_delivery_box">

                                <path d="M60.27,42.62l-.43-1.19a3.851,3.851,0,0,0-4.2-2.42c-.05.01-.09.01-.14.02V13.5c0-.01-.01-.02-.01-.04a.8.8,0,0,0-.06-.3.637.637,0,0,0-.02-.07c-.01-.01-.01-.03-.02-.04l-3-6a.988.988,0,0,0-.89-.55h-27a1,1,0,0,0-.83.45l-4,6a.388.388,0,0,0-.06.11c-.01.03-.03.05-.04.08a1.372,1.372,0,0,0-.07.34V32.92c-.15.02-.3.04-.45.07l-4.08.92V30.5a2.006,2.006,0,0,0-2-2H5.5a2.006,2.006,0,0,0-2,2v25a2.006,2.006,0,0,0,2,2h7.47a2.006,2.006,0,0,0,2-2V52.89L31.5,55.88a12.715,12.715,0,0,0,7-.69l19.52-7.73A3.8,3.8,0,0,0,60.27,42.62ZM12.97,55.5H5.5v-25h7.47ZM42.5,8.5h8.38l2,4H42.5Zm-8,0h6v4h-6Zm0,6h6v12l-2.4-1.8a1,1,0,0,0-1.2,0l-2.4,1.8Zm-9.46-6H32.5v4H22.37Zm-3.54,6h11v14a.977.977,0,0,0,.55.89,1,1,0,0,0,1.05-.09l3.4-2.55,3.4,2.55a1.029,1.029,0,0,0,.6.2.908.908,0,0,0,.45-.11.977.977,0,0,0,.55-.89v-14h11V39.28c-.5.07-1.01.15-1.5.24-1.01.16-2.06.34-3.08.44a53.558,53.558,0,0,1-7.03.33,53.365,53.365,0,0,1-5.91-.52,3.661,3.661,0,0,0-.33-2.03,4,4,0,0,0-2.54-2.08l-9.48-2.57a9.536,9.536,0,0,0-2.13-.32ZM57.28,45.6,37.77,53.33a10.729,10.729,0,0,1-5.91.58L14.97,50.86V35.96l4.52-1.02a7.517,7.517,0,0,1,3.62.08l9.47,2.57a1.992,1.992,0,0,1,1.27,1.02,1.725,1.725,0,0,1,.03,1.44,2.036,2.036,0,0,1-2.02,1.17l-8.22-1.7a1,1,0,0,0-.41,1.96l8.28,1.71a.355.355,0,0,0,.1.02c.14.01.27.02.4.02a4.068,4.068,0,0,0,3.2-1.55,56.551,56.551,0,0,0,6.62.61,55.905,55.905,0,0,0,7.3-.34c1.08-.11,2.16-.29,3.2-.46,1.14-.19,2.32-.39,3.48-.49a1.969,1.969,0,0,1,2.15,1.12l.43,1.18A1.814,1.814,0,0,1,57.28,45.6Z" />

                                <path d="M49.5,31.5h-6a2.006,2.006,0,0,0-2,2v2a2.006,2.006,0,0,0,2,2h6a2.006,2.006,0,0,0,2-2v-2A2.006,2.006,0,0,0,49.5,31.5Zm0,4h-6v-2h6Z" />

                            </g>

                        </svg>
                        <h1 class="text-center">ยืม</h1>
                    </a>
                    <br>
                </div>

                <div class="mb-5 ms-auto me-auto">
                    <a href="admin.php" class="box1">
                        <svg fill="#365486" height="200px" width="200px" version="1.1" id="XMLID_138_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            viewBox="0 0 24 24" xml:space="preserve">
                            <g id="compliance">
                                <g>
                                    <path d="M22,24H2V2h5V0h10v2h5V24z M4,22h16V4h-3v3H7V4H4V22z M9,5h6V2H9V5z M11,18.4l-3.7-3.7l1.4-1.4l2.3,2.3l5.3-5.3l1.4,1.4
			L11,18.4z" />
                                </g>
                            </g>
                        </svg>
                        <h1 class="text-center mt-4">สำหรับเจ้าหน้าที่</h1>
                    </a>
                    <br>
                </div>
            </div>

        </div>
    </div>

    <footer class="mt-5 footer mt-auto py-3" style="background: #fff;">

        <marquee style="font-weight: bold; font-size: 1rem"><span style="font-size: 1rem" class="text-muted text-center">Design website by นายอภิชน ประสาทศรี , พุฒิพงศ์ ใหญ่แก้ว &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Coding โดย นายอานุภาพ ศรเทียน &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ควบคุมโดย นนท์ บรรณวัฒน์ นักวิชาการคอมพิวเตอร์ ปฏิบัติการ</span>
        </marquee>

    </footer>

    <script src="bootstrap/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>