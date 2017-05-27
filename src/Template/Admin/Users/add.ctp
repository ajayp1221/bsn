<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body contain-lg">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-primary">Add New User</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-offset-1 col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <?= $this->Form->create($user, ['class' => 'form']) ?>

                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" required="required" />
                                    <label>Email</label>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" required="required" />
                                    <label>Password</label>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="first_name" class="form-control" required="required" />
                                    <label>First Name</label>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="last_name" class="form-control" required="required" />
                                    <label>Last Name</label>
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <?php
                                    $gender = array('0' => 'Female','1' => 'Male');
                                    echo $this->Form->input('gender', ['options' => $gender, 'class' => "form-control select2-list", 'data-placeholder' => "Select an item", 'label' => false]);
                                    ?>
                                </div>
                                <div class="form-group">
                                    <label>Role</label>
                                    <?php
                                    $role = array('ADMIN' => 'ADMIN');
                                    echo $this->Form->input('role', ['options' => $role, 'class' => "form-control select2-list", 'data-placeholder' => "Select an item", 'label' => false]);
                                    ?>
                                </div>
                                <input type="hidden" name="status" value="1" />
                                <div class="card-actionbar">
                                    <div class="card-actionbar-row">
                                        <button type="submit" class="btn btn-success ink-reaction">Save</button>
                                    </div>
                                </div>
                                <?= $this->Form->end() ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?= $this->element('side') ?>
</div>
<?= $this->element('formjs') ?>
