<?php
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');

if (!isset($_SESSION['lich'])) {
    $_SESSION['lich'] = [];
}

$message = "";

function layTrangThai($ngay, $gio, $trangthai)
{
    if ($trangthai == "Đã hủy") {
        return "Đã hủy";
    }

    $bat_dau = strtotime($ngay . " " . $gio);
    $ket_thuc = $bat_dau + (30 * 60);
    $hien_tai = time();

    if ($hien_tai < $bat_dau) {
        return "Chưa diễn ra";
    } elseif ($hien_tai >= $bat_dau && $hien_tai < $ket_thuc) {
        return "Đang diễn ra";
    } else {
        return "Đã hoàn thành";
    }
}

function kiemTraTrungLich($lich, $giangvien, $ngay, $gio)
{
    foreach ($lich as $item) {
        $trangthai = $item['trangthai'] ?? "";

        if ($trangthai == "Đã hủy") {
            continue;
        }

        if (
            $item['giangvien'] == $giangvien &&
            $item['ngay'] == $ngay &&
            $item['gio'] == $gio
        ) {
            return true;
        }
    }

    return false;
}

if (isset($_POST['datlich'])) {
    $ten = trim($_POST['ten'] ?? "");
    $masv = trim($_POST['masv'] ?? "");
    $giangvien = trim($_POST['giangvien'] ?? "");
    $ngay = trim($_POST['ngay'] ?? "");
    $gio = trim($_POST['gio'] ?? "");
    $noidung = trim($_POST['noidung'] ?? "");

    if (
        empty($ten) ||
        empty($masv) ||
        empty($giangvien) ||
        empty($ngay) ||
        empty($gio) ||
        empty($noidung)
    ) {
        $message = "Vui lòng nhập đầy đủ thông tin.";
    } else {
        $thoigian = strtotime($ngay . " " . $gio);

        if ($thoigian === false) {
            $message = "Ngày hoặc giờ không hợp lệ.";
        } elseif ($thoigian <= time()) {
            $message = "Không thể đặt lịch trong thời gian đã qua.";
        } elseif (
            kiemTraTrungLich(
                $_SESSION['lich'],
                $giangvien,
                $ngay,
                $gio
            )
        ) {
            $message = "Giảng viên đã có lịch vào thời gian này. Vui lòng chọn giờ khác.";
        } else {
            $lich_moi = [
                "ten" => $ten,
                "masv" => $masv,
                "giangvien" => $giangvien,
                "ngay" => $ngay,
                "gio" => $gio,
                "noidung" => $noidung,
                "trangthai" => "Chưa diễn ra"
            ];

            $_SESSION['lich'][] = $lich_moi;
            $message = "Đặt lịch thành công.";
        }
    }
}

if (isset($_POST['huylich'])) {
    $id = intval($_POST['id'] ?? -1);

    if (isset($_SESSION['lich'][$id])) {
        $lich = $_SESSION['lich'][$id];

        $trangthai = layTrangThai(
            $lich['ngay'],
            $lich['gio'],
            $lich['trangthai'] ?? ""
        );

        if ($trangthai == "Chưa diễn ra") {
            $_SESSION['lich'][$id]['trangthai'] = "Đã hủy";
            $message = "Đã hủy lịch hẹn.";
        } else {
            $message = "Không thể hủy lịch này.";
        }
    }
}

$hom_nay = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hệ thống đặt lịch tư vấn</title>
<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 30px;
    font-family: Arial, sans-serif;
    background: #fff4f7;
    color: #444;
}

.container {
    width: 950px;
    max-width: 100%;
    margin: auto;
}

h1 {
    text-align: center;
    color: #c05278;
    margin-bottom: 30px;
    font-size: 30px;
}

.box {
    background: #fff;
    border: 1px solid #f0ccd8;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 4px 12px rgba(192, 82, 120, 0.08);
}

h2 {
    margin-top: 0;
    color: #c05278;
    font-size: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f5d8e1;
}

label {
    display: block;
    margin-top: 15px;
    margin-bottom: 6px;
    font-weight: bold;
    color: #555;
}

input,
select,
textarea {
    width: 100%;
    padding: 11px;
    border: 1px solid #e3bdca;
    border-radius: 7px;
    background: #fffafb;
    color: #444;
    font-size: 14px;
}

input:focus,
select:focus,
textarea:focus {
    outline: none;
    border-color: #d56f91;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(213, 111, 145, 0.10);
}

textarea {
    height: 90px;
    resize: vertical;
}

.datlich {
    width: 100%;
    padding: 12px;
    margin-top: 20px;
    border: none;
    border-radius: 7px;
    background: #d56f91;
    color: white;
    font-size: 15px;
    cursor: pointer;
}

.datlich:hover {
    background: #c4577c;
}

.message {
    padding: 13px;
    margin-bottom: 20px;
    border-radius: 7px;
    background: #fff0f4;
    border: 1px solid #efc5d2;
    color: #a84467;
    text-align: center;
    font-weight: bold;
}

.note {
    margin-top: 8px;
    font-size: 13px;
    color: #999;
}

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    background: white;
}

th {
    background: #f7dce5;
    color: #8f405d;
    border: 1px solid #edc5d2;
    padding: 11px;
    font-size: 14px;
    text-align: center;
}

td {
    border: 1px solid #efd7df;
    padding: 10px;
    font-size: 14px;
    text-align: center;
}

tr:nth-child(even) {
    background: #fff9fb;
}

tr:hover {
    background: #fff0f4;
}

.chua-dien-ra,
.dang-dien-ra,
.da-hoan-thanh,
.da-huy {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}

.chua-dien-ra {
    background: #fff0c7;
    color: #916c00;
}

.dang-dien-ra {
    background: #d8f3e4;
    color: #217044;
}

.da-hoan-thanh {
    background: #dcecff;
    color: #376a9b;
}

.da-huy {
    background: #eee;
    color: #888;
}

.huy {
    padding: 6px 10px;
    border: 1px solid #d58aa3;
    border-radius: 5px;
    background: #fff;
    color: #bd5278;
    cursor: pointer;
}

.huy:hover {
    background: #d56f91;
    color: white;
}

.khong-co {
    text-align: center;
    padding: 20px;
    color: #999;
}

select option:disabled {
    color: #aaa;
    background: #eee;
}

@media (max-width: 600px) {
    body {
        padding: 15px;
    }

    .box {
        padding: 18px;
    }

    h1 {
        font-size: 24px;
    }

    th,
    td {
        font-size: 12px;
        padding: 7px;
    }
}
</style>
</head>
<body>

<div class="container">

<h1>Hệ thống đặt lịch tư vấn</h1>

<?php if ($message != "") { ?>
<div class="message">
    <?= htmlspecialchars($message) ?>
</div>
<?php } ?>

<div class="box">
<h2>Đặt lịch tư vấn</h2>

<form method="POST">

<label>Họ và tên sinh viên</label>
<input type="text" name="ten" placeholder="Nhập họ và tên" required>

<label>Mã sinh viên</label>
<input type="text" name="masv" placeholder="Nhập mã sinh viên" required>

<label>Giảng viên Ngoại ngữ</label>
<select name="giangvien" required>
    <option value="">-- Chọn giảng viên --</option>
    <option value="Nguyễn Thị Lan - Tiếng Anh">Nguyễn Thị Lan - Tiếng Anh</option>
    <option value="Trần Thị Hương - Tiếng Anh">Trần Thị Hương - Tiếng Anh</option>
    <option value="Lê Minh Anh - Tiếng Trung">Lê Minh Anh - Tiếng Trung</option>
    <option value="Phạm Thu Hà - Tiếng Nhật">Phạm Thu Hà - Tiếng Nhật</option>
    <option value="Nguyễn Hoàng Nam - Tiếng Hàn">Nguyễn Hoàng Nam - Tiếng Hàn</option>
</select>

<label>Ngày hẹn</label>
<input type="date" name="ngay" id="ngay" min="<?= $hom_nay ?>" required>
<div class="note">Không thể chọn ngày đã qua.</div>

<label>Giờ hẹn</label>
<select name="gio" id="gio" required>
    <option value="">-- Chọn giờ --</option>
    <option value="07:30">07:30</option>
    <option value="08:00">08:00</option>
    <option value="08:30">08:30</option>
    <option value="09:00">09:00</option>
    <option value="09:30">09:30</option>
    <option value="10:00">10:00</option>
    <option value="13:30">13:30</option>
    <option value="14:00">14:00</option>
    <option value="14:30">14:30</option>
    <option value="15:00">15:00</option>
    <option value="15:30">15:30</option>
    <option value="16:00">16:00</option>
</select>
<div class="note">Nếu chọn hôm nay, các giờ đã qua sẽ tự động bị khóa.</div>

<label>Nội dung cần tư vấn</label>
<textarea name="noidung" placeholder="Nhập nội dung cần tư vấn..." required></textarea>

<button type="submit" name="datlich" class="datlich">Đặt lịch</button>

</form>
</div>

<div class="box">
<h2>Lịch của sinh viên</h2>

<?php if (empty($_SESSION['lich'])) { ?>

<p class="khong-co">Chưa có lịch hẹn nào.</p>

<?php } else { ?>

<div class="table-wrapper">
<table>
<tr>
    <th>STT</th>
    <th>Giảng viên</th>
    <th>Ngày</th>
    <th>Giờ</th>
    <th>Nội dung</th>
    <th>Trạng thái</th>
    <th>Thao tác</th>
</tr>

<?php
$stt = 1;

foreach ($_SESSION['lich'] as $id => $item) {

    $trangthai = layTrangThai(
        $item['ngay'],
        $item['gio'],
        $item['trangthai'] ?? ""
    );
?>

<tr>
    <td><?= $stt ?></td>

    <td>
        <?= htmlspecialchars($item['giangvien']) ?>
    </td>

    <td>
        <?= date("d/m/Y", strtotime($item['ngay'])) ?>
    </td>

    <td>
        <?= htmlspecialchars($item['gio']) ?>
    </td>

    <td>
        <?= htmlspecialchars($item['noidung']) ?>
    </td>

    <td>
        <?php if ($trangthai == "Chưa diễn ra") { ?>
            <span class="chua-dien-ra">Chưa diễn ra</span>
        <?php } elseif ($trangthai == "Đang diễn ra") { ?>
            <span class="dang-dien-ra">Đang diễn ra</span>
        <?php } elseif ($trangthai == "Đã hoàn thành") { ?>
            <span class="da-hoan-thanh">Đã hoàn thành</span>
        <?php } else { ?>
            <span class="da-huy">Đã hủy</span>
        <?php } ?>
    </td>

    <td>
        <?php if ($trangthai == "Chưa diễn ra") { ?>

        <form method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy lịch này không?');">
            <input type="hidden" name="id" value="<?= $id ?>">
            <button type="submit" name="huylich" class="huy">Hủy</button>
        </form>

        <?php } else {
            echo "-";
        } ?>
    </td>
</tr>

<?php
    $stt++;
}
?>

</table>
</div>

<?php } ?>

</div>
</div>

<script>
const ngayInput = document.getElementById("ngay");
const gioSelect = document.getElementById("gio");

function capNhatGio() {
    const ngayChon = ngayInput.value;

    if (!ngayChon) {
        for (let option of gioSelect.options) {
            option.disabled = false;
        }
        return;
    }

    const now = new Date();
    const nam = now.getFullYear();
    const thang = String(now.getMonth() + 1).padStart(2, "0");
    const ngay = String(now.getDate()).padStart(2, "0");
    const ngayHienTai = `${nam}-${thang}-${ngay}`;

    for (let option of gioSelect.options) {
        if (!option.value) {
            continue;
        }

        if (ngayChon === ngayHienTai) {
            const [gio, phut] = option.value.split(":");
            const thoiGianChon = new Date();

            thoiGianChon.setHours(
                parseInt(gio),
                parseInt(phut),
                0,
                0
            );

            option.disabled = thoiGianChon <= now;
        } else {
            option.disabled = false;
        }
    }

    if (
        gioSelect.selectedOptions.length > 0 &&
        gioSelect.selectedOptions[0].disabled
    ) {
        gioSelect.value = "";
    }
}

ngayInput.addEventListener("change", capNhatGio);
capNhatGio();
setInterval(capNhatGio, 30000);
</script>

</body>
</html>