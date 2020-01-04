$(function () {
//    var mailURL = 'http://localhost/medic_ic/';
    var mailURL = 'https://catalintoma.ro/api/';
//    var apiRoot = '/api/v2/';

    $('form input').on('keypress', function (e) {
        if (e.which === 13) {
            $('.btn-search').click();
        }
    });

    $('.btn-search').on('click', function () {
        var $searchForm = $('#search_form');
        if (!$searchForm[0].checkValidity()) {
            //Fake submit button and remove it after click call
            $('<input type="submit">').hide().appendTo($searchForm).click().remove();
        } else {
            var key = $('#search_key').val();
//            var token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1NjE2MTQzODgsIm5iZiI6MTU2MTYxNDM4OCwianRpIjoiYzg5Y2NhZGEtMDFkNC00ZGZmLTkwMjMtNzhmNWI3NDNlYWMwIiwiZXhwIjoxNTYxNjE1Mjg4LCJpZGVudGl0eSI6ImU4ZmFkMTliLTRhMTItNDg1ZS1hODFmLWU5NDUwOWU2OGI4OCIsImZyZXNoIjpmYWxzZSwidHlwZSI6ImFjY2VzcyIsInVzZXJfY2xhaW1zIjp7ImlkIjoiZThmYWQxOWItNGExMi00ODVlLWE4MWYtZTk0NTA5ZTY4Yjg4IiwiZW1haWwiOiJjYXRhbGluLnRvbWFAZ21haWwuY29tIiwibnVtZSI6IkNhdGFsaW4gVG9tYSIsImp1ZGV0IjoxMjIsImFjdGl2ZSI6dHJ1ZSwicm9sZSI6MTAwLCJ0ZWxlZm9uIjoiMDcyMiJ9fQ.xYkGj1t-8k257WhErjuP-s7uNLF4BwXeqNGrDKF-pIM';
            var url = mailURL + 'dashboard/searchByKey/' + key;
            jQuery.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                beforeSend: function () {
                    $('.overlay').removeClass('hidden');
                },
                complete: function () {
                    $('.overlay').addClass('hidden');
                },
                success: function (data) {
                    var res = data.results;
                    console.log(res);
                    var tbody = '';
                    $.each(res, function (key, val) {
                        var tempTr = '<tr>';
                        tempTr += '<td>' + val.nume + '</td>';
                        tempTr += '<td>' + val.initiala + '</td>';
                        tempTr += '<td>' + val.prenume + '</td>';
                        tempTr += '<td>' + val.cuim + '</td>';
                        tempTr += '<td>' + val.id + '</td>';
                        tempTr += '<td><input type="checkbox" name="cbImport" value="' + val.id + '" onchange="manageCBs()"/></td>';
                        tempTr += '</tr>';
                        tbody += tempTr;
                    });
                    $('.searchedData').html(tbody);
                    if (res.length > 0) {
                        $('#allSearchedData').val(JSON.stringify(res));
                    } else {
                        $('#allSearchedData').val('');
                    }
                },
                error: function (jqxhr, status, msg) {
                    //error code
                    alert("Problem! Try again later.");
                }
            });
        }
    });

    $('.btn-insert').on('click', function () {
        var toImportData = [];
        $.each($("input[name='cbImport']:checked"), function () {
            toImportData.push($(this).val());
        });
        console.log(toImportData);

        //All rows data to be filtered
        var allResData = JSON.parse($('#allSearchedData').val());
        console.log(allResData);

        var filteredData = allResData.filter(d => toImportData.includes(d.id));
        console.log(filteredData);

        var url = mailURL + 'dashboard/importSelectedData/';
        jQuery.ajax({
            url: url,
            type: 'POST',
            data: {
                'filteredData': JSON.stringify(filteredData)
            },
            beforeSend: function () {
                $('.overlay').removeClass('hidden');
            },
            complete: function () {
                $('.overlay').addClass('hidden');
            },
            success: function (data) {
                console.log(data);
                $('#search_key').val('');
                $('.searchedData').html('');
                $('.btnInsWrapper').addClass('hidden');
                alert("Inserted Successfully");                
            },
            error: function (jqxhr, status, msg) {
                //error code
                alert("There is some problem. Please try after sime time.");
            }
        });
    });

    ///////////////////////////
    //Manage checkbox selection
    ///////////////////////////

    $("input[name='checkAll']").on('change', function () {
        if ($(this).is(":checked")) {
            $.each($("input[name='cbImport']"), function () {
                $(this).prop('checked', true);
            });
        } else {
            $.each($("input[name='cbImport']"), function () {
                $(this).prop('checked', false);
            });
        }
        manageCBs();
    });
    manageCBs = function () {
        var checkedLen = $("input[name='cbImport']:checked").length;
        var noOfRows = $('.searchedData tr').length;
        if (checkedLen === noOfRows) {
            $("input[name='checkAll']").prop('checked', true);
        } else {
            $("input[name='checkAll']").prop('checked', false);
        }
        if (checkedLen > 0) {
            $('.btnInsWrapper').removeClass('hidden');
        } else {
            $('.btnInsWrapper').addClass('hidden');
        }
    };
});


