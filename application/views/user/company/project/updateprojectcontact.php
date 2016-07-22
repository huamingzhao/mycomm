 <div class="right">
                        <h2 class="user_right_title">
                            <span>我的项目</span>
                            <div class="clear"></div>
                        </h2>
                        <div class="my_business_new">
                            <div class="project_detial project_release">
                                <form action="/company/member/project/doUpdateProjectContact" method="post" id="publishForm">
                                <ul class="info info_contant">
                                    <li class="title"><b>项目联系人信息</b></li>
                                    <li class="label"><font>*</font>姓名：</li>
                                    <li class="content">
                                        <input type="hidden" value="<?=$project_id;?>" name="project_id">
                                        <input type="hidden" value="<?=$type;?>" name="type">
                                        <input type="text" value="<?=arr::get($result, 'project_contact_people', '')?>" id="contactPeople" name="project_contact_people"/>
                                        <span class="info">请填写姓名</span>
                                    </li>
                                    <li class="label label2">职位：</li>
                                    <li class="content">
                                        <input type="text" value="<?=arr::get($result, 'project_position', '')?>" id="project_position" name="project_position" />
                                        <font>联系人在公司所担任的职务</font>
                                    </li>
                                    <li class="label"><font>*</font>手机号码：</li>
                                    <li class="content content2">
                                        <input type="text" value="<?=arr::get($result, 'project_handset', '')?>" id="handSet" maxlength="11" name="project_handset" />
                                        <font>方便投资者和我们随时与您取得联系</font>
                                        <span class="info">请填写手机号码</span>
                                    </li>
                                    <li class="label">公司座机号码：</li>
                                    <li class="content">
                                        <input type="text" id="tel" name="project_phone" maxlength="15" value="<?=arr::get($result['project_phone'], 0, '')?>" />
                                    </li>
                                    <li class="label label2">转：</li>
                                    <li class="content">
                                        <input type="text" id="project_phone_fj" maxlength="5" name="project_phone_fj" value="<?=arr::get($result['project_phone'], 1, '')?>" />
                                    </li>
                                    <li class="clear"></li>
                                </ul>
                                <ul class="info">
                                    <li class="content"><button class="red sent">提交</button></li>
                                    <li class="clear"></li>
                                </ul>
                                    </form>
                            </div>
                        </div>
                    </div>
                    <?php echo URL::webjs("zhaos_box.js")?>
                    <script type="text/javascript">initFormCheck();</script>