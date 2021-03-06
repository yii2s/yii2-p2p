require(["jquery","common","placeholder","modal","tooltip"],function($,c){
    $(function(){

        var accountdt = $("#accountSide dt");
        accountdt.on("click",function(e){
            var sd = $(this).siblings("dd");
            if(sd.is(":hidden")){
                sd.slideDown();
            }else{
                sd.slideUp();
            }
            sd.length > 0?e.preventDefault():true;
        });

        var singleWay = $(".singleWay");
        singleWay.on("click",function(){
            singleWay.children("img").removeClass("active");
            $(this).children("img").addClass("active");
            $(this).children("input").prop("checked",true);
        });

        var toggleItem = $("#toggleItem"),payHide = $("#payHide"),originText = toggleItem.html();
        toggleItem.on("click",function(){
            if(payHide.is(":hidden")){
                payHide.slideDown();
                $(this).html("收起");
            }else{
                payHide.slideUp();
                $(this).html(originText);
            }
        });

        var chargeMoney = $("#chargeMoney");
        chargeMoney.keyup(function(){
            $(this).val(c.filterNum($(this).val()));
        })
        chargeMoney.change(function(){
            checkMoney();
        });


        function checkMoney(){
            chargeMoney.val(c.filterNum(chargeMoney.val()));
            if(chargeMoney.val() >= 10){
                chargeMoney.siblings(".errorColor").addClass("hide");
                return true;
            }else{
                chargeMoney.siblings(".errorColor").removeClass("hide");
                return false;
            }
        }

        $("#rechargeBtn").on("click",function(){
            checkMoney();
            if(checkMoney()){
                $("#rechargeModal").modal();
                return false;
            }else{
                return false;
            }
        });

        $(".showTips").hover(function(){
            $(this).tooltip("toggle");
        });

        $(".switchWrap").on("click",function(){
            $(this).children(".switch").toggleClass("switchToggle");
        })

        $(".myWalletAreaTap li").on("click",function(){
            $(this).addClass("active").siblings("li").removeClass("active");
            $(".myWalletSingle").eq($(this).index()).removeClass("hide").siblings(".myWalletSingle").addClass("hide");
        });


        $("#walletSubmit").submit(function(){
            var myWalletNumber = $("#myWalletNumber"),myWalletOver = parseFloat($("#myWalletOver").html()).toFixed(2);
            if(parseFloat(myWalletOver - parseFloat(myWalletNumber.val()).toFixed(2)).toFixed(2) > 0 ){
                $(".errorColor").addClass("hide");
            }else{
                $(".errorColor").removeClass("hide");
                return false;
            }
        });

        var myWalletNumber = $("#myWalletNumber");

        $("#walletInForm").submit(function(){
            var myMoneyNow = parseFloat($("#myMoney").html()).toFixed(2);
            if(parseFloat(myMoneyNow - parseFloat(myWalletNumber.val()).toFixed(2)).toFixed(2) > 0 ){
                $(".errorColor").addClass("hide");
            }else{
                $(".errorColor").removeClass("hide");
                return false;
            }
        });

        myWalletNumber.keyup(function(){
            $(this).val(c.filterNum($(this).val()));
            $(this).val() > 0? true:$(this).val("");
            var rate = $('#package-rate').text() / 100;
            $("#willBe").html(parseFloat($(this).val()* rate).toFixed(2));
        })

        //my messages
        $(".messagesTable tr").on("click",function(){
            $(this).toggleClass("toggleContent");

        });

        $("#allMsg").on("change",function(){
            if($(this).prop("checked") == false){
                $(".msgCheck").prop("checked",false);
            }else{
                $(".msgCheck").prop("checked",true);
            }
        });

        var codeSend = $("#codeSend"),idTime,countLoop;
        codeSend.on("click",function(){
            idTime = 59;
            $(this).attr("disabled", "disabled").addClass("btnDisabled");
            countDown();
            countLoop = setInterval(countDown, 1000);
        });

        function countDown(){
            codeSend.html("重新发送"+idTime+"s");
            if (idTime > 0) {
                return idTime--;
            } else {
                clearInterval(countLoop);
                codeSend.removeAttr("disabled").removeClass("btnDisabled").html("发送短信验证码");
            }
        }

        //logout
        var logTime = 5,countTime = $("#countTime");
        function countBack(){
            if(logTime > 1){
                logTime--;
                countTime.html(logTime);
            }else{
                window.location.href = "/";
            }
        }

        if($("#logOut").length > 0){
            setInterval(countBack,1000);
        }

    });
});