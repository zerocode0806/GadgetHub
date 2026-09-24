<div class="container-fluid px-4">
                        <h1 class="mt-4">Kasir</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Kasir</li>
                        </ol>

                        <a href="?page=kasir_tambah" class="btn btn-primary btn-sm mb-2"><i class="fas fa-plus"></i> Tambah Data</a>
                        <hr>
                        <table class="table table-bordered">
                            <tr>
                                <th>Nama Kasir</th>
                                <th>Alamat</th>
                                <th>No Telepon</th>
                                <th>Aksi</th>
                            </tr>

                            <?php 
                                $query = mysqli_query($koneksi, "SELECT * FROM pelanggan");
                                while($data = mysqli_fetch_array($query)){
                                    ?>
                                    <tr>
                                        <td><?php echo $data['nama_pelanggan']; ?></td>
                                        <td><?php echo $data['alamat']; ?></td>
                                        <td><?php echo $data['no_telepon']; ?></td>
                                        <td  class="text-center">
                                            <a href="?page=kasir_ubah&&id=<?php echo $data['id_pelanggan']; ?> " class="btn btn-success"> <i class="fas fa-edit"></i> Ubah</a>
                                            <a href="?page=kasir_hapus&&id=<?php echo $data['id_pelanggan']; ?>" onclick="return confirm('Yakin ingin menghapus data ?')" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Hapus</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </table>
                        
                    </div>