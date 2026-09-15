<?php 
    foreach ($media as $row) {
 ?>
<style>
  .copyButton {
  background-color: #4caf50; /* Warna latar belakang */
  color: white; /* Warna teks */
  padding: 5px 10px; /* Padding */
  border: none; /* Tanpa border */
  cursor: pointer; /* Kursor berubah saat dihover */
}
</style>
<script>

function copyToClipboard(text) {
  var textarea = document.createElement("textarea");
  textarea.value = text;
  document.body.appendChild(textarea);
  textarea.select();
  document.execCommand("copy");
  document.body.removeChild(textarea);
  alert("Teks berhasil disalin ke clipboard!");
}
</script>
 <script type="text/javascript">
     $(#)
 </script>
<div class="portfolio-content">
    <div class="cbp-l-project-title">Detail Media</div>
    <div class="cbp-l-project-subtitle"></div>
    <div class="cbp-l-project-container">
        <div class="cbp-l-project-details">
            <div class="cbp-l-project-details-title">
                <span>Gambar Media</span>
            </div>
            <?php foreach ($detail as $baris) { ?>
            <a href="<?php echo $baris['media_link'];?>" class="cbp-lightbox">
                <img src="<?php echo $baris['media_link'];?>" alt="">
            </a>
            <?php } ?>
        </div>
        <div class="cbp-l-project-desc">
            <div class="cbp-l-project-desc-title">
                <span>Keterangan</span>
            </div>
            <ul class="cbp-l-project-details-list">
                <li>
                    <strong>Nama File</strong><?php echo $row['judul']; ?>
                </li>
                <li>
                    <strong>Deskripsi</strong><?php echo $row['deskripsi']; ?>
                </li>
                <li>
                    <strong>Alternatif Teks</strong><?php echo $row['alt_teks']; ?>
                </li>
                <li>
                    <strong>Tipe</strong><?php echo $row['tipe']; ?>
                </li>
                <li>
                    <strong>Tgl Upload</strong><?php echo $row['tgl_upload']; ?>
                </li>
                <li>
                    <strong>Tgl Perubahan</strong><?php echo $row['tgl_perubahan']; ?>
                </li>
                <li>
                    <strong>Author</strong><?php echo $row['author']; ?>
                </li>
                <li>
                    <strong>Original</strong><a href="<?php echo base_url('uploads/'.$row['judul']); ?>" alt="" target="_blank"><?php echo base_url('uploads/'.$row['judul']); ?></a>
                </li>
                <?php foreach ($detail as $baris) { ?>
                <li>
                    <strong><?php echo $baris['jenis_ukuran']; ?> </strong> 
                    <button id="copyButton<?php echo $baris['mediadetail_id'];?>" class="copyButton">Copy Link</button>  
                    <a href="<?php echo $baris['media_link']; ?>" target="_blank" alt=""><?php echo $baris['media_link']; ?></a>
                    <script>
                    document.getElementById("copyButton<?php echo $baris['mediadetail_id'];?>").addEventListener("click", function() {
                      copyToClipboard("<?php echo $baris['media_link'];?>");
                    });
                    </script>
                </li>
               
                <?php } ?>
<!--                <li>
                    <strong>Status</strong><?php echo $row['status']; ?>
                </li>-->
            </ul>
        </div>
    </div>
</div>

    <?php }?>