<div class="container-fluid px-4">
                        <h1 class="mt-4">Kasir</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Kasir</li>
                        </ol>

                        <a href="/admin/cashiers" class="btn btn-danger btn-sm mb-2"><i class="fas fa-plus"></i> Kembali</a>
                        <hr>
                        
                        <form method="post">
                            <table class="table table-bordered">
                                <tr>
                                    <td width="200">Nama Kasir</td>
                                    <td width="5">:</td>
                                    <td><input class="form-control" type="text" name="nama_pelanggan"></td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>:</td>
                                    <td>
                                        <textarea name="alamat" rows="5" class="form-control"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>No Telepon</td>
                                    <td>:</td>
                                    <td><input class="form-control" type="number" step="0" name="no_telepon"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        <button type="reset" class="btn btn-danger">Reset</button>
                                    </td>
                                </tr>
                            </table>
                        </form>

                    </div>