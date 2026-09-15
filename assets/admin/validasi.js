var FormControls=function() {
  if (page_action=='tambah') {
    var e=function() {
        $("#form_tambah2").validate( {
            rules: {
                jml_hal: {
                    required: !0
                }
                , no_klas: {
                    required: !0
                }
                , isbn: {
                    required: !0,
                    remote: {
                      url: base_site+"/cek2",
                      type: "post",
                      data: {
                        no_klas: function() {
                          return $( "#no_klas" ).val();
                        },
                        isbn: function() {
                          return $( "#isbn" ).val();
                        }
                      }
                    }
                }
                , thn_terbit: {
                    required: !0, digits: !0
                }
                , penerbit: {
                    required: !0
                }
                , ukuran_fisik: {
                    required: !0
                }
                , penulis: {
                    required: !0
                }
                , judul: {
                    required: !0
                }
                , tajuksubyek: {
                    required: !0
                }
            }, invalidHandler:function(e, r) {
                var i=$("#m_form_1_msg");
                i.removeClass("m--hide").show(), mApp.scrollTo(i, -200)
            }
            , submitHandler:function(e) {
              e.submit();
            }
        })
      };
      return {init:function() {e()} }
  }
  // if (page_action=='edit') {
  //   alert('ini');
  //   var e=function() {
  //       $("#form_edit").validate( {
  //           rules: {
  //               jml_hal: {
  //                   required: !0
  //               }
  //               , no_klas: {
  //                   required: !0
  //               }
  //               , isbn: {
  //                   required: !0,
  //                   remote: {
  //                     url: base_site+"/cek2",
  //                     type: "post",
  //                     data: {
  //                       no_klas: function() {
  //                         return $( "#no_klas" ).val();
  //                       },
  //                       isbn: function() {
  //                         return $( "#isbn2" ).val();
  //                       }
  //                     }
  //                   }
  //               }
  //               , thn_terbit: {
  //                   required: !0, digits: !0
  //               }
  //               , penerbit: {
  //                   required: !0
  //               }
  //               , ukuran_fisik: {
  //                   required: !0
  //               }
  //               , penulis: {
  //                   required: !0
  //               }
  //               , judul: {
  //                   required: !0
  //               }
  //               , tajuksubyek: {
  //                   required: !0
  //               }
  //           }, invalidHandler:function(e, r) {
  //               var i=$("#m_form_2_msg");
  //               i.removeClass("m--hide").show(), mApp.scrollTo(i, -200)
  //           }
  //           , submitHandler:function(e) {
  //             alert('ini');
  //             e.submit();
  //           }
  //       })
  //     };
  //     return {init:function() {e()} }
  // }
  if (page_action=='set_inv') {
    r=function() {
        $("#tambah_inv").validate( {
            rules: {
                no_barcode: {
                    required: !0
                }
                , no_inv: {
                    required: !0
                }
                , tgl_inv: {
                    required: !0
                }
                , asal: {
                    required: !0
                }
            }, submitHandler:function(r) {
                // ajax adding data to database
                $.ajax({
                url: tambah_inv,
                type: "POST",
                data: $(r).serialize(),
                dataType: "JSON",
                success: function(data) {
                    if (data.status === true) {
                        swal({
                            title: 'Berhasil',
                            text: data.msg || 'Inventaris Berhasil Ditambah',
                            type: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        swal({
                            title: 'Gagal',
                            text: data.msg || 'Inventaris Gagal Ditambah',
                            type: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Error jaringan/server (misal 500, timeout, dll.)
                    swal({
                        title: 'Error Server',
                        text: 'Terjadi kesalahan saat menghubungi server. Silakan coba lagi atau hubungi administrator.',
                        type: 'error',
                        confirmButtonText: 'OK'
                    });
                    console.error('AJAX Error:', textStatus, errorThrown);
                }
            });
            }
        })
      },
      s=function() {
          $("#edit_inv").validate( {
              rules: {
                  no_barcode2: {
                      required: !0
                  }
                  , no_inv2: {
                      required: !0
                  }
                  , tgl_inv2: {
                      required: !0
                  }
                  , asal2: {
                      required: !0
                  }
              }, submitHandler:function(s) {
                var barcode = document.getElementById('no_barcode2').value;
                url = edit_site_inv+barcode;
                  // ajax adding data to database
                  $.ajax({
                      url : url,
                      type: "POST",
                      data: $(s).serialize(),
                      dataType: "JSON",
                       success: function(data) {
                        if (data.status === true) {
                            swal({
                                title: 'Berhasil',
                                text: data.msg || 'Inventaris Berhasil Diubah',
                                type: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            swal({
                                title: 'Gagal',
                                text: data.msg || 'Inventaris Gagal Diubah',
                                type: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Error jaringan/server (misal 500, timeout, dll.)
                        swal({
                            title: 'Error Server',
                            text: 'Terjadi kesalahan saat menghubungi server. Silakan coba lagi atau hubungi administrator.',
                            type: 'error',
                            confirmButtonText: 'OK'
                        });
                        console.error('AJAX Error:', textStatus, errorThrown);
                    }
                  });
              }
          })
        }
      return {init:function() {r(),s()} }
  }

}();
jQuery(document).ready(function() {
		FormControls.init();
});
