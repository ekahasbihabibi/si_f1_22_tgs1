
<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user"  method="post" action="<?= base_url('pendaftaran'); ?>">

                            <div class="form-group">
                                    <input type="number" class="form-control form-control-user" id="npm" name="npm"
                                        placeholder="NPM" value="<?= set_value('npm'); ?>" required>
                                        <?= form_error('npm', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                            <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="name" name="name"
                                        placeholder="Nama Lengkap" value="<?= set_value('name'); ?>" required>
                                        <?= form_error('name', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                              
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="email" name="email"
                                        placeholder="Email Address" value="<?= set_value('email'); ?>" required>
                                        <?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="password1" name="password1" placeholder="Password" required>
                                            <?= form_error('password1', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="password2" name="password2" placeholder="Repeat Password" required>
                                            <?= form_error('password1', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                              
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="<?= base_url('lupa_password') ?>">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="<?= base_url('login') ?>">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
