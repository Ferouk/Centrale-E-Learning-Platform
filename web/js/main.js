$(function () {

    $("select#entype").on('change', function () {
        hidden = $(".hidden");
        step1 = $(".step1");
        switch($(this).val()){
            case "0":
                hidden.hide();
                step1.hide();
                $(".student").show();
                break;
            case "1":
                hidden.hide();
                step1.hide();
                $(".parent").show();
                break;
            case "2":
                hidden.hide();
                step1.hide();
                $(".teacher").show();
                break;
            case "3":
                hidden.hide();
                step1.hide();
                $(".personel").show();
                break;
            case "4":
                hidden.hide();
                step1.hide();
                $(".admin").show();
                break;
            case "":
                hidden.hide();
                step1.show();
                break;
        }

    });






    $(".form-group .input-group.date").datepicker({
        endDate: "-17y",
        format:"mm/dd/yyyy",
        language: "fr"
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.old').hide();
                preview = $(".preview");
                preview.show();
                preview.attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#img-input").change(function(){
        readURL(this);
    });

    $(".hided").hide();

    $("#grouped").click(function() {
        if($(this).is(":checked")) {
            $(".hided").show(300);
        } else {
            $(".hided").hide(200);
        }
    });
    $("#public").click(function() {
        if($(this).is(":checked")) {
            $(".classes").hide(200);
            $(".groups").hide(200);
            $(".departments").hide(200);
        }
    });
    $("#internal").click(function() {
        if($(this).is(":checked")) {
            $(".classes").hide(200);
            $(".groups").hide(200);
            $(".departments").hide(200);
        }
    });
    $("#department").click(function() {
        if($(this).is(":checked")) {
            $(".classes").hide(200);
            $(".groups").hide(200);
            $(".departments").show(300);
        }
    });

    $("#classe").click(function() {
        if($(this).is(":checked")) {
            $(".departments").hide(200);
            $(".groups").hide(200);
            $(".classes").show(300);
        }
    });
    $("#groups").click(function() {
        if($(this).is(":checked")) {
            $(".departments").hide(200);
            $(".classes").hide(200);
            $(".groups").show(300);
        }
    });


    $("tr[id*='student-']").each(function() {
        calculeMoy($(this));
    });

    function calculeMoy(tr){
        tp = parseFloat(tr.find('.tp').val());
        ds1 = parseFloat(tr.find('.ds1').val());
        ds2 = parseFloat(tr.find('.ds2').val());
        exam = parseFloat(tr.find('.exam').val());
        moy = 0.;

        //Moy calculation logic here
        if(ds1 && !ds2 && tp && exam){
            moy = (tp + ds1 + (exam*2)) / 4;
        }else if(ds1 && !ds2 && !tp && exam){
            moy = (ds1 + (exam*2)) / 3;
        }else if(ds1 && ds2 && !tp && exam){
            moy = (ds2 + ds1 + (exam*2)) / 4;
        }else{
            moy = (tp + ds1 + ds2 + (exam*2)) / 5;
        }

        moy = Math.round(moy * 100) / 100

        if(moy){
            tr.find('.moy').text(moy);
        }else{
            tr.find('.moy').text("--");
        }
    }

    function calculeMoy2(tr){
        tp = parseFloat(tr.find('.tp').text());
        ds1 = parseFloat(tr.find('.ds1').text());
        ds2 = parseFloat(tr.find('.ds2').text());
        exam = parseFloat(tr.find('.exam').text());
        moy = 0.;
        //Moy calculation logic here
        if(ds1 && !ds2 && tp && exam){
            moy = (tp + ds1 + (exam*2)) / 4;
        }else if(ds1 && !ds2 && !tp && exam){
            moy = (ds1 + (exam*2)) / 3;
        }else if(ds1 && ds2 && !tp && exam){
            moy = (ds2 + ds1 + (exam*2)) / 4;
        }else{
            moy = (tp + ds1 + ds2 + (exam*2)) / 5;
        }

        moy = Math.round(moy * 100) / 100

        if(moy){
            tr.find('.moy').text(moy);
        }else{
            tr.find('.moy').text("--");
        }
    }


    cycle = $("#group_cycle").val();

    if (cycle = "ING") {


        $('#calc').click(function () {
            $("tr[id*='student-']").each(function() {
                calculeMoy($(this));
            });
        });

        $("td[id*='note']").each(function() {
            calculeMoy2($(this));
            $(".tp").hide();
            $(".ds1").hide();
            $(".ds2").hide();
            $(".exam").hide();
        });

        MoyG = 0.;
        CoeffG = 0.;

        $("tr[id*='gm']").each(function() {
            gmCoeff = parseFloat($(this).find('#gmCoeff').text());
            courses = $(this).find('#courses');
            course = courses.find("tr[id*='course']");
            totM = 0.;
            totCoeff = 0.;

            credit = 0;

            course.each(function() {
                mCoeff = parseFloat($(this).find('#mCoeff').text());
                moy = parseFloat($(this).find('.moy').text());
                moyM = moy*mCoeff;
                totM = totM+moyM;
                totCoeff = totCoeff + mCoeff;

                if(moy < 8){
                    credit = credit + 1;
                }

            });

            gmMoy = totM / totCoeff;
            gmMoy = Math.round(gmMoy * 100) / 100;

            if(gmMoy){
                $(this).find("#GMMoy").text(gmMoy);

                if(gmMoy < 8){
                    $(this).find("#credit").text(credit);
                }else{
                    $(this).find("#credit").text(0);

                }

            }else{
                $(this).find("#GMMoy").text("--");
            }

            MoyG = MoyG + (gmMoy * gmCoeff);
            CoeffG = CoeffG + gmCoeff;
        });



        credits = 0;

        $("td[id*='credit']").each(function() {

            if(parseInt($(this).text())){
                credits += parseInt($(this).text());
            }
        });

        $("#credits").text(credits)
        GeneralMoy = MoyG/CoeffG;
        GeneralMoy = Math.round(GeneralMoy * 100) / 100
        if (GeneralMoy) {
            $("#Moyenne").html("<strong>"+GeneralMoy+"</strong>");

            if(GeneralMoy >= 10){
                $("#decision").html('<strong class="pull-right text-success">Admisible</strong>');
            }else if( GeneralMoy < 10){
                $("#decision").html('<strong class="pull-right text-warning">Ajourné(e)</strong>');
            }else{
                $("#decision").html('<strong class="pull-right text-danger">Refusé(e)</strong>');
            }

        }else{
            $("#Moyenne").html("<strong>--</strong>");

        }

    }else{
        //Master ou licence
    }

});
