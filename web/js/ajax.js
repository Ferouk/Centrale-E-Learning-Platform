$(function () {

    genTotal();

    $("button#addUser").click(function (e) {
        e.preventDefault();
        var data = $('form').serialize();

        $('.theform').hide();
        $('.loader').show();
        $.ajax({
            type: 'POST',
            url: Routing.generate('admin_userAdd'),
            data: data,
            processData: false,
            success: function (res) {
                if(res.type == 'success'){
                    $('#usersList').append('<tr>\ ' +
                        '<td>'+res.entity.id+'</td>\ ' +
                        '<td>'+res.entity.name+'</td>\ ' +
                        '<td>'+res.entity.surname+'</td>\ ' +
                        '<td><small>'+res.entity.type+'</small></td>\ ' +
                        '<td><small>'+res.entity.privilege+'</small></td>\ ' +
                        '<td>\ '+
                        '<a href="#" class="btn btn-sm btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></a>\ '+
                        '<button data-toggle="modal" data-target="#deleteUser" id="btnDeleteUser" class="btn btn-sm btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>\ '+
                        '<a href="#" class="btn btn-sm btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>\ '+
                        '</td>\ ' +
                        '</tr>\ ');
                    $('span.alert-msg-success').text(res.message);
                    $('.loader').hide();
                    $('.alert-success').show();
                    $('form').find("input[type=text],input[type=email],input[type=password], textarea").val("");
                    $("select#entype").val("");
                    $("select#privilege").val("");
                    $(".hidden").hide();
                    $(".step1").show();
                    setTimeout(function(){
                        $('.alert-success').hide();
                        $('.theform').show();
                    }, 5000);

                }else{
                    $('span.alert-msg-error').text(res.message);
                    $('.loader').hide();
                    $('.alert-danger').show();
                    setTimeout(function(){
                        $('.alert-danger').hide();
                        $('.theform').show();
                    }, 5000);
                }
            },
            error: function (xhr, status, err) {
                console.log(xhr);
                $('span.alert-msg-error').text("Un erreur s'est produit!");
                $('.loader').hide();
                $('.alert-danger').show();
                setTimeout(function(){
                    $('.alert-danger').hide();
                    $('.theform').show();
                }, 5000);
            }

        });
    });

    $("button#addPrivilege").click(function(e){
        e.preventDefault();
        if($(this).hasClass("disabled") == false){
            $(this).addClass("disabled");
            btn = $(this);
            $('.loader').show();
            var data = $('form').serialize();
            $.ajax({
                type: 'POST',
                url: Routing.generate('admin_privilegeAdd'),
                data: data,
                processData: false,
                success: function (res) {
                    if(res.type == 'success'){
                        $('.privilegesList').append("<tr><td>"+res.entity.title+"</td><td></td></tr>");
                        $('span.alert-msg-success').text(res.message);
                        $('.loader').hide();
                        $('.alert-success').show();
                        btn.removeClass("disabled");
                        $('form').find("input[type=text],input[type=email],input[type=password], textarea").val("");
                        setTimeout(function(){
                            $('.alert-success').hide();
                            $('.theform').show();
                        }, 5000);

                    }else{
                        $('span.alert-msg-error').text(res.message);
                        $('.loader').hide();
                        $('.alert-danger').show();
                        btn.removeClass("disabled");
                        setTimeout(function(){
                            $('.alert-danger').hide();
                            $('.theform').show();
                        }, 5000);
                    }
                },
                error: function (xhr, status, err) {
                    console.log(xhr);
                    $('span.alert-msg-error').text("Un erreur s'est produit!");
                    $('.loader').hide();
                    $('.alert-danger').show();
                    btn.removeClass("disabled");
                    setTimeout(function(){
                        $('.alert-danger').hide();
                    }, 5000);
                }
            });
        }
    });

    $("button#btnDeleteUser").click(function (e) {
        btn = $(this);
        $('#deleteUser').on('show.bs.modal', function (e) {
            user = btn.parent().parent();
            id = user.find("td#userId").text();
            name = user.find("td#userName").text();
            surname = user.find("td#userSurname").text();

            $("span.modal-user-name").text(name);
            $("span.modal-user-surname").text(surname);
            $("input#modal-user-id").val(id);

        });
    });

    $("button#Confirm-delete-user").click(function (e) {
       e.preventDefault();

        id = $("input#modal-user-id").val();
        user = $("td:contains('"+id+"')").parent();


        $.ajax({
            type: 'DELETE',
            url: Routing.generate('admin_userDelete', {"id": id}),
            success: function (res) {
                user.remove();
                $('#deleteUser').modal('hide')
            },
            error: function (xhr, status, err) {
                console.log(xhr);
            }

        });


    });

    $("#btnAddGM").click(function () {
        $(this).hide();
        list = $("#list").text();
        title = $("input#title");
        numGM = $("input#NumGM");
        parts = title.val().split(".");
        last = $('tbody tr#GMRow:last-child th').text();
        if(list){
            if(parts.length < 3){
                title.val(title.val() + ".1");
                numGM.val(1);
            }
        }else{
            lastOne = parseInt(last.split(".")[2]) + 1;

            if(parts.length <= 3){
                title.val(parts[0] +"."+ parts[1] + "." + lastOne);
                numGM.val(lastOne);

            }
        }
    })

    $('#addGM').on('hidden.bs.collapse', function () {
        $("#btnAddGM").show();
        $("input#coeff").val("");
    });

    $("#btnCancelGM").click(function (e) {
        e.preventDefault();
        $("#addGM").collapse('hide');
    });

    $("#btnNewGM").click(function (e) {
        e.preventDefault();
        btn = $(this);

        if(btn.hasClass("disabled") == false){
            btn.addClass("disabled");
            var data = $('form#formAddGM').serialize();
            $(".loader").show();

            $.ajax({
                type: 'POST',
                url: Routing.generate('admin_curriculumAdd'),
                data: data,
                processData: false,
                success:function (res) {
                    if(res.entity){
                        tbody = $("tbody");
                        $("td#list").parent().remove();
                        row = '<tr id="GMRow">'+
                            '<th style="text-align: center; vertical-align:middle;" scope="row">'+res.entity.title+
                            '<input type="hidden" value="'+res.entity.id+'">'+
                            '</th>'+
                        '<td colspan="8" class="text-muted" id="noModule"><h5>Pas de modules</h5></td>'+
                        '<td id="GMCoeff" style="text-align: center; vertical-align:middle;">'+res.entity.coeff+'</td>'+
                        '<td id="GMActions" style="text-align: center; vertical-align:middle;">'+
                            '<button id="BtnNewModule" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#addModule"><i class="fa fa-book" aria-hidden="true"></i></button> '+
                           '<a href="#" class="btn btn-sm btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></a> '+
                            '<button data-toggle="modal" data-target="#deleteGM" id="btnDeleteGM" class="btn btn-sm btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button> '+
                            '</td>'+
                        '</tr>';
                        tbody.append(row);
                        updateTotal(null,null,null,null,null,res.entity.coeff);
                        $(".loader").hide();
                        $("#addGM").collapse('hide');
                        btn.removeClass("disabled");
                    }else{
                        $(".loader").hide();
                        $("#addGM").collapse('hide');
                        btn.removeClass("disabled");
                    }

                },
                error: function (xhr, status, err) {
                    console.log(xhr);
                    $(".loader").hide();
                    btn.removeClass("disabled");
                }
            });
        }
    });

    $("button#BtnNewModule").click(function (e) {
        tr = $(this).parent().parent();
        $(this).addClass("btnClicked");
        gm = tr.find("th");
        id = gm.find("input").val();

        $("input#gm").val(id);


    });

    $("button#BtnAddNewModule").click(function (e) {
        e.preventDefault();
        $("#loaderModule").show();
        var data = $('form.form').serialize();
        btn = $(this);
        btn.addClass("disabled");
        btn.prop("disabled",true);

        $.ajax({
            type: 'POST',
            url: Routing.generate('admin_curriculumAddModule'),
            data: data,
            processData: false,
            success:function (res) {
                tr = $("button.btnClicked").parent().parent();
                gmCoeff = tr.find("td#GMCoeff");
                gmActions = tr.find("td#GMActions");

                tot = 0.;
                if(res.entity.c){
                    tot += parseFloat(res.entity.c);
                }
                if(res.entity.tp){
                    tot += parseFloat(res.entity.tp);
                }
                if(res.entity.td){
                    tot += parseFloat(res.entity.td);
                }
                if(tr.find("td#noModule").length){
                tr.find("td#noModule").remove();

                    tr.find("th").after("<td>"+res.entity.module+"</td>"+
                        "<td id='CellSemester'>"+res.entity.semestre+"</td>"+
                        "<td id='CellType'>"+res.entity.type+"</td>"+
                        "<td id='CellC'>"+res.entity.c+"</td>"+
                        "<td id='CellTD'>"+res.entity.td+"</td>"+
                        "<td id='CellTP'>"+res.entity.tp+"</td>"+
                        "<td id='CellTOT'>"+tot+"</td>"+
                        "<td id='CellMD'>"+res.entity.coeff+"</td>"
                    );

                    tr.find("th").attr('rowspan', 1);
                    gmCoeff.attr('rowspan', 1);
                    gmActions.attr('rowspan', 1);
                }else{
                    rowspan = parseInt(tr.find("th").attr("rowspan")) + 1;
                    tr.find("th").attr('rowspan', rowspan);
                    gmCoeff.attr('rowspan', rowspan);
                    gmActions.attr('rowspan', rowspan);

                    tr.after("<tr><td>"+res.entity.module+"</td>"+
                        "<td id='CellSemester'>"+res.entity.semestre+"</td>"+
                        "<td id='CellType'>"+res.entity.type+"</td>"+
                        "<td id='CellC'>"+res.entity.c+"</td>"+
                        "<td id='CellTD'>"+res.entity.td+"</td>"+
                        "<td id='CellTP'>"+res.entity.tp+"</td>"+
                        "<td id='CellTOT'>"+tot+"</td>"+
                        "<td id='CellMD'>"+res.entity.coeff+"</td></tr>"
                    );
                }
                updateTotal(res.entity.c,res.entity.td,res.entity.tp,tot,res.entity.coeff,null);
                $("#loaderModule").hide();
                setTimeout(function(){
                    $('#addModule').modal('hide');
                }, 1000);

                btn.removeClass("disabled");
                btn.prop("disabled",false);
                $('form.form').find("input[type=text]").val("");
                $('form.form').find('select[name="semestre"]').val("1");
                $('form.form').find('select[name="type"]').val("MIX");
                $("button.btnClicked").removeClass("btnClicked");
            },
            error: function (xhr, status, err) {
                console.log(xhr);
            }
        });
    });

    $(document).on("click", "button#btnDeleteGM", function () {
       tr = $(this).parent().parent();
        id = tr.find("th input").val();
        name = tr.find("th").text();

        $("span.modal-gm-name").text(name);
        $("input#modal-gm-id").val(id);

    });

    $("button#Confirm-delete-gm").click(function (e) {
       e.preventDefault();
        if($(this).hasClass("disabled") == false){
            $("#loaderDeleteGM").show();
            id = $("input#modal-gm-id").val();
            gm = $('th input[value="'+id+'"]').parent().parent();
            btn = $(this);
            btn.addClass("disabled");

            $.ajax({
                type: 'DELETE',
                url: Routing.generate('admin_gmDelete', {"id": id}),
                success: function (res) {
                    $("#loaderDeleteGM").hide();
                    gm.remove();
                    if($("tr#GMRow").length == 0){
                        $("tfoot").hide();
                        $("tbody").append('<tr><td colspan="11" class="text-muted" id="list"><h3>La liste est vide</h3></td></tr>');
                    }
                    genTotal();
                    $('#deleteGM').modal('hide');
                    btn.removeClass("disabled");
                },
                error: function (xhr, status, err) {
                    $("#loaderDeleteGM").hide();
                    console.log(xhr);
                    btn.removeClass("disabled");
                }

            });
        }

    });

    function genTotal() {
        list = $("td#list");
       if(list.length == 0){
           CellC = $("td#CellC");
           CellTD = $("td#CellTD");
           CellTP = $("td#CellTP");
           CellTOT = $("td#CellTOT");
           CellMD = $("td#CellMD");
           CellGM = $("td#GMCoeff");

           totalC = $("td#totalC");
           totalTD = $("td#totalTD");
           totalTP = $("td#totalTP");
           totalTOT = $("td#totalTOT");
           totalMD = $("td#totalMD");
           totalGM = $("td#totalGM");

           totC = 0.;
           totTD = 0.;
           totTP = 0.;
           totTOT = 0.;
           totMD = 0.;
           totGM = 0.;

           CellC.each(function () {
               totC = totC + parseFloat($(this).text());
           });

           CellTD.each(function () {
               totTD = totTD + parseFloat($(this).text());
           });


           CellTP.each(function () {
               totTP = totTP + parseFloat($(this).text());
           });

            CellTOT.each(function () {
                totTOT = totTOT + parseFloat($(this).text());
            });

           CellMD.each(function () {
               totMD = totMD + parseFloat($(this).text());
           })

            CellGM.each(function () {
                totGM = totGM + parseFloat($(this).text());
            })

           totalC.text(totC);
           totalTD.text(totTD);
           totalTP.text(totTP);
           totalTOT.text(totTOT);
           totalMD.text(totMD);
           totalGM.text(totGM);

       }
    }

    function updateTotal(c,td,tp,tot,md,gm) {
        totalC = $("td#totalC");
        totalTD = $("td#totalTD");
        totalTP = $("td#totalTP");
        totalTOT = $("td#totalTOT");
        totalMD = $("td#totalMD");
        totalGM = $("td#totalGM");

        if(c){
            totC = parseFloat(totalC.text()) + parseFloat(c);
            totalC.text(totC)
        }
        if(td){
            totTD = parseFloat(totalTD.text()) + parseFloat(td);
            totalTD.text(totTD)
        }
        if(tp){
            totTP = parseFloat(totalTP.text()) + parseFloat(tp);
            totalTP.text(totTP)
        }
        if(tot){
            totTOT = parseFloat(totalTOT.text()) + parseFloat(tot);
            totalTOT.text(totTOT)
        }
        if(md){
            totMD = parseFloat(totalMD.text()) + parseFloat(md);
            totalMD.text(totMD)
        }
        if(gm){
            totGM = parseFloat(totalGM.text()) + parseFloat(gm);
            totalGM.text(totGM)
        }
        if($("tfoot").is(":hidden")){
            $("tfoot").show();
        }
    }

});