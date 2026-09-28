<?php
// Set CLI environment variables for CI
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['SERVER_NAME'] = 'lib.polkesri.ac.id';
$_SERVER['HTTP_HOST'] = 'lib.polkesri.ac.id';
$_SERVER['REQUEST_URI'] = '/';

chdir(__DIR__ . '/..');

if (session_status() === PHP_SESSION_NONE) {
    session_id('testsess123456');
    session_start();
}
if (!isset($_SESSION)) {
    $_SESSION = [];
}

ob_start();
require_once 'index.php';
ob_end_clean();

$CI =& get_instance();
$CI->load->model('Md_vwanggota');
$CI->load->model('Md_siperpus_sysuser');
$CI->load->model('Md_mediasosial');
$CI->load->helper('menu_generator');

function get_header_html($CI) {
    $data = [
        'mediasosial' => [],
        'page_content' => 'dashboard'
    ];
    return $CI->load->view('front-header', $data, true);
}

echo "Running SSO and Navbar assert tests...\n";

// 1. Verify Guest navbar state
$CI->session->unset_userdata(['idsys', 'username', 'login_type', 'default', 'login_via', 'member', 'member_nama', 'member_tipe']);
$html = get_header_html($CI);
assert(strpos($html, 'Sign In') !== false, "Guest must see 'Sign In'");
assert(strpos($html, 'navbar-user-name') === false, "Guest must not see navbar-user-name");
assert(strpos($html, 'Logout') === false, "Guest must not see Logout");
echo "✓ Test 1: Guest navbar displays Sign In correctly\n";

// 2. Verify Student (Mahasiswa) SSO login & navbar state
$mhs_info = $CI->Md_vwanggota->getInfoAnggota('P032415301001');
assert(!empty($mhs_info), "Student P032415301001 must exist in database");
assert($mhs_info->kategori === 'm', "P032415301001 must have kategori 'm'");

$CI->session->set_userdata([
    'member'      => 'P032415301001',
    'member_nama' => $mhs_info->nama,
    'member_tipe' => 'Mahasiswa',
    'login_type'  => 'student',
    'login_via'   => 'sso',
]);

$html = get_header_html($CI);
assert(strpos($html, htmlspecialchars($mhs_info->nama)) !== false, "Navbar must show student name");
assert(strpos($html, 'Mahasiswa') !== false, "Navbar must show 'Mahasiswa' badge");
assert(strpos($html, 'home/logoutmember') !== false, "Navbar must link to member logout");
assert(strpos($html, '> Dashboard<') === false, "Navbar must NOT display Dashboard for student");
assert(strpos($html, 'Sign In') === false, "Navbar must NOT display Sign In when logged in");
echo "✓ Test 2: Student SSO displays student name, Mahasiswa badge, and no Dashboard\n";

// 3. Verify Regular Pegawai SSO login & navbar state
$peg_info = $CI->Md_vwanggota->getInfoAnggota('1471095407950061');
assert(!empty($peg_info), "Pegawai 1471095407950061 must exist in database");
assert($peg_info->kategori === 'k', "1471095407950061 must have kategori 'k'");

$CI->session->unset_userdata(['idsys', 'username']);
$CI->session->set_userdata([
    'member'      => '1471095407950061',
    'member_nama' => $peg_info->nama,
    'member_tipe' => 'Pegawai',
    'login_type'  => 'pegawai',
    'login_via'   => 'sso',
]);

$html = get_header_html($CI);
assert(strpos($html, htmlspecialchars($peg_info->nama)) !== false, "Navbar must show pegawai name");
assert(strpos($html, 'Pegawai') !== false, "Navbar must show 'Pegawai' badge");
assert(strpos($html, 'home/logoutmember') !== false, "Navbar must link to member logout");
assert(strpos($html, '> Dashboard<') === false, "Navbar must NOT display Dashboard for regular pegawai");
assert(strpos($html, 'Sign In') === false, "Navbar must NOT display Sign In when logged in");
echo "✓ Test 3: Regular Pegawai SSO displays pegawai name, Pegawai badge, and no Dashboard\n";

// 4. Verify Admin / Staf Pegawai SSO login & navbar state
$admin_users = $CI->Md_siperpus_sysuser->getUserById('aisyah');
assert(!empty($admin_users), "Admin user 'aisyah' must exist in database");
$admin = $admin_users[0];

$CI->session->unset_userdata(['member', 'member_nama']);
$CI->session->set_userdata([
    'idsys'       => $admin->idsysuser,
    'username'    => $admin->name,
    'avatar'      => 'Male-1.png',
    'login_type'  => 'admin',
    'default'     => 'manage_artikel',
    'login_via'   => 'sso',
    'member'      => $admin->idsysuser,
    'member_nama' => $admin->name,
    'member_tipe' => 'Pegawai',
]);

$html = get_header_html($CI);
assert(strpos($html, htmlspecialchars($admin->name)) !== false, "Navbar must show admin pegawai name");
assert(strpos($html, 'Pegawai SSO') !== false, "Navbar must show 'Pegawai SSO' badge");
assert(strpos($html, 'admin/manage_artikel') !== false, "Navbar user name must link to admin dashboard");
assert(strpos($html, 'admin/logout') !== false, "Navbar must link to admin logout");
assert(strpos($html, '> Dashboard<') === false, "Standalone Dashboard link must be replaced by user name");
assert(strpos($html, 'Sign In') === false, "Navbar must NOT display Sign In when logged in");
echo "✓ Test 4: Admin Pegawai SSO replaces Dashboard with user name linking to dashboard\n";

echo "\nALL SSO & NAVBAR TESTS PASSED!\n";
