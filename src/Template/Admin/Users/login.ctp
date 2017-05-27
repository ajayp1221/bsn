<section class="section-account">
    <div class="img-backdrop" style="background-image: url('../../assets/img/img16.jpg')"></div>
    <div class="spacer"></div>
    <div class="card contain-sm style-transparent">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <br/>
                    <span class="text-lg text-bold text-primary">MATERIAL ADMIN</span>
                    <br/><br/>
                    <?= $this->Form->create(NULL,['class' => 'form floating-label','method' => 'post','accept-charset'=>"utf-8"]) ?>
                        <div class="form-group">
                            <input type="email"  name="email" class="form-control" placeholder="Enter your email" />
                            <label for="email"> </label>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" placeholder="Enter your password" />
                            <label for="password"></label>
                            <p class="help-block"><a href="#">Forgotten?</a></p>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-xs-6 text-left">
                                <div class="checkbox checkbox-inline checkbox-styled">
                                    <label>
                                        <input name="remember" type="checkbox" value="1"> <span>Remember me</span>
                                    </label>
                                </div>
                            </div><!--end .col -->
                            <div class="col-xs-6 text-right">
                                    <button class="btn btn-primary btn-raised" type="submit">Login</button>
                            </div><!--end .col -->
                        </div><!--end .row -->
                    <?= $this->Form->end() ?>
                </div><!--end .col -->
            </div><!--end .row -->
        </div><!--end .card-body -->
    </div><!--end .card -->
</section>