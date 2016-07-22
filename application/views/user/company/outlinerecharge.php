<?php echo URL::webcss("account_zg.css")?>
<script src="<?php echo URL::webstatic("js/My97DatePicker/WdatePicker.js")?>" type="text/javascript"></script>
<script language="javascript">
function checkPrice(price){
    if(price==0)
        return false;
    return (/^(([1-9]\d*)|\d)(\.\d{1,2})?$/).test(price.toString());
}
//得到字符串的真实长度（双字节换算为两个单字节）
function getStrActualLen(sChars)
{
return sChars.replace(/[^\x00-\xff]/g,"xx").length;
}

// 截取固定长度子字符串 sSource为字符串iLen为长度
function getInterceptedStr(sSource, iLen)
{
if(sSource.replace(/[^\x00-\xff]/g,"xx").length <= iLen)
{
return sSource;
}

var str = "";
var l = 0;
var schar;
for(var i=0; schar=sSource.charAt(i); i++)
{
str += schar;
l += (schar.match(/[^\x00-\xff]/) != null ? 2 : 1);
if(l >= iLen)
{
break;
}
}

return str;
}
$(function(){
    $("#form_sumit").click(function(){
        var order_realname = $('#order_realname').val();
        var order_bank_name = $('select[name=order_bank_name]').val().Trim();
        var order_line_time = $('#order_line_time').val();
        var order_account = $('#order_account').val();
        var order_remarks = $('#order_remarks').val();
        var order_line_note = $('#order_line_note').val();
        var order_code = $('#order_code').val().Trim();
        var order_bank_name_add = $('#order_bank_name_add').val().Trim();
        var re = /^[0-9,]*$/;
        if(checkPrice(order_account)==false){
            $(".showmsg").html('对不起，您填写的金额信息不正确，无法提交。');
            $("#getcards_deletebox .text").removeClass("suc");
            $("#getcards_deletebox").slideDown("500",function(){
                $("#getcards_opacity").show();
            })
            return false;
        }
        else if(Math.ceil(order_account)>100000){
            $(".showmsg").html('对不起，您填写的金额超出最大限制，无法提交。');
            $("#getcards_deletebox .text").removeClass("suc");
            $("#getcards_deletebox").slideDown("500",function(){
                $("#getcards_opacity").show();
            });
            return false;
        }
        else if (order_realname==""){
            $(".showmsg").html('对不起，您的汇款人信息填写不完善，无法提交。');
            $("#getcards_deletebox .text").removeClass("suc");
            $("#getcards_deletebox").slideDown("500",function(){
                $("#getcards_opacity").show();
            });
            return false;
        }
        else if (order_bank_name==""){
            $(".showmsg").html('对不起，您的汇款方式不完善，无法提交。');
            $("#getcards_deletebox .text").removeClass("suc");
            $("#getcards_deletebox").slideDown("500",function(){
                $("#getcards_opacity").show();
            });
            return false;
        }
        else if (order_line_time==""){
            $(".showmsg").html('对不起，您的汇款时间填写不完善，无法提交。');
            $("#getcards_deletebox .text").removeClass("suc");
            $("#getcards_deletebox").slideDown("500",function(){
                $("#getcards_opacity").show();
            });
            return false;
        }
        else if (getStrActualLen(order_code)==0||!re.test(order_code)||getStrActualLen(order_code)>25){
            $(".showmsg").html('请输入字符数不超过25个，由数字组成的汇票号码');
            $("#getcards_deletebox .text").removeClass("suc");
            $("#getcards_deletebox").slideDown("500",function(){
                $("#getcards_opacity").show();
            });
            return false;
        }
        else if (order_remarks==""){
            $(".showmsg").html('对不起，您的汇款事由填写不完善，无法提交。');
            $("#getcards_deletebox .text").removeClass("suc");
            $("#getcards_deletebox").slideDown("500",function(){
                $("#getcards_opacity").show();
            });
            return false;
        }

        if(order_bank_name=='0'){
            if (getStrActualLen(order_bank_name_add)==0){
                $(".showmsg").html('请输入正确的汇款方式,字符数不超过16个');
                $("#getcards_deletebox .text").removeClass("suc");
                $("#getcards_deletebox").slideDown("500",function(){
                    $("#getcards_opacity").show();
                });
                return false;
            }
            if (getStrActualLen(order_bank_name_add)>16){
                $(".showmsg").html('请输入正确的汇款方式,字符数不超过16个');
                $("#getcards_deletebox .text").removeClass("suc");
                $("#getcards_deletebox").slideDown("500",function(){
                    $("#getcards_opacity").show();
                });
                return false;
            }
        }

            $.ajax({
                type: "post",
                dataType: "json",
                url: "/company/member/ajaxcheck/outlinerecharge/",
                data: 'order_realname='+order_realname+"&order_bank_name="+order_bank_name+"&order_line_time="+order_line_time+"&order_account="+order_account+"&order_remarks="+order_remarks+"&order_line_note="+order_line_note+"&order_bank_name_add="+order_bank_name_add+"&order_code="+order_code,
                complete :function(){
                },
                success: function(msg){
                    if(msg['status']){
                        $(".showmsg").html('您的汇款信息已经添加成功，请耐心等待审核。');
                        $("#getcards_deletebox .text").addClass("suc");
                        $("#getcards_deletebox").slideDown("500",function(){
                            $("#getcards_opacity").show();
                        });
                        $(".cz_suc").hide();
                        $(':input','#outlinerecharge').not(':button, :submit, :reset, :hidden').val('');
                    }
                    else{
                        $(".showmsg").html(msg['msg']);
                        $("#getcards_deletebox").slideDown("500",function(){
                            $("#getcards_opacity").show();
                        });
                        $("#getcards_deletebox .text").removeClass("suc");
                    }
                }
            });
            return false;
    });

    //关闭
    $("#getcards_deletebox a.close").click(function(){
        $(this).parent().slideUp("500",function(){
            $("#getcards_opacity").hide();
        })
        return false;
    })
    //取消
    $(".cz_suc a.cancel").click(function(){
        $("#getcards_deletebox").slideUp("500",function(){
            $("#getcards_opacity").hide();
        })
        return false;
    })
    //确定
    $("#getcards_deletebox a.ensure").click(function(){
        $("#getcards_deletebox").slideUp("500",function(){
            $("#getcards_opacity").hide();
        })
        return false;
    })
    $("#order_bank_name").change(function(){
        if($(this).val() == '0'){
            $(this).siblings("input").show();
        }else{
            $(this).siblings("input").hide();
            $('#order_bank_name_add').val('');
        }
    });
    if($("#order_bank_name").val() == '0'){
        $("#order_bank_name").siblings("input").show();
    }else{
        $("#order_bank_name").siblings("input").hide();
        $('#order_bank_name_add').val('');
    }
});
</script>
    <!--右侧开始-->
<style>
.bankForm input {border: 1px solid #DBDBDB;color: #4A4A4A;height: 28px;line-height: 28px;width: 170px;margin-left:0; float:left;}
.bankForm textarea {border: 1px solid #DBDBDB;color: #4A4A4A;height: 109px;padding-top: 10px;resize: vertical;width: 482px;margin-left:0; float:left;}
</style>
    <div id="right">
        <div id="right_top"><span>银行汇款</span><div class="clear"></div></div>
        <div id="right_con">
            <div class="bank_zg" style="padding: 10px 20px 0 19px;">
                <h4>一句话平台专用银行账号</h4>
                <dl>
                    <dt><i class="nyyh"></i>开户银行：中国农业银行 -上海支行</dt>
                    <dd>
                        <span>开户银行公司名称：</span>
                        <span>上海通路快建网络服务外包有限公司</span>
                    </dd>
                    <dd>
                        <span>银行账号：</span>
                        <span class="crad_num">9559 9349 0582 3676 154</span>
                    </dd>
                </dl>
                <dl>
                    <dt><i class="jsyh"></i>开户银行：中国建设银行 -上海支行</dt>
                    <dd>
                        <span>开户银行公司名称：</span>
                        <span>上海通路快建网络服务外包有限公司</span>
                    </dd>
                    <dd>
                        <span>银行账号：</span>
                        <span class="crad_num">6222 7349 0581 2455 874</span>
                    </dd>
                </dl>
                <dl>
                    <dt><i class="gsyh"></i>开户银行：中国工商银行 -上海支行</dt>
                    <dd>
                        <span>开户银行公司名称：</span>
                        <span>上海通路快建网络服务外包有限公司</span>
                    </dd>
                    <dd>
                        <span>银行账号：</span>
                        <span class="crad_num">9558 8397 3493 5869 897</span>
                    </dd>
                </dl>
                <p>沟通时请注明汇款人姓名、汇款方式（邮局或汇款方式名称）、汇款票号、汇款时间、汇款金额和汇款事由，以便我
们查证并在1-3个工作日内为您开通服务，谢谢！</p>
                <p class="contact_kf">如对银行汇款还有疑问或不明白的用户，可在线客服或拔打客服电话 <font>4001015908</font> 详情咨询</p>
            </div>
        </div>
    </div>
    <!--右侧结束-->
    <div class="clear"></div>
</div>
<div id="getcards_opacity"></div>
<div id="getcards_deletebox">
    <a class="close" href="#">关闭</a>
    <div class="text">
        <p class="showmsg" style="padding-left:35px;"></p>
        <p class="cz_suc"><a class="ensure" href=""><img src="<?php echo URL::webstatic('/images/getcards/ensure1.jpg')?>"></a>
           <a class="cancel" href=""><img src="<?php echo URL::webstatic('/images/getcards/cancel1.jpg')?>"></a>
        </p>
        <input id="getcards_deletebox_hid" type="hidden" value="0"></input>
    </div>
</div>