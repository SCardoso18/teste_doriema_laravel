$("#province").change(function(event){
    $.get("municipios/"+event.target.value+"",function(response,province){
        console.log(response);

        $("#district").empty();
        for(i=0; i<response.length; i++)
        {
            $("#district").append("<option value='"+response[i].id+"'> "+response[i].name+"</option>");
        }
    });
});




// $("#province").change(event => {
//     $.get(`municipios/${event.target.value}`, function(res, prov){
//         $("#district").empty();
//         res.forEach(element => {
//             $("#distrit").append(`<option value=${element.id}> ${element.name} </option>`)
//         });
//     });
// });
