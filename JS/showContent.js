$(function(){
    
    console.log('Привет, это старый js ))');
    init_get();
    init_post();
    new_load_post();
    new_load_get();
});

function init_get() 
{
    $('a.ajaxArticleBodyByGet').one('click', function(){
        var contentId = $(this).attr('data-contentId');
        console.log('ID статьи = ', contentId); 
        showLoaderIdentity();
        $.ajax({
            url:'/ajax/showContentsHandler.php?articleId=' + contentId, 
            dataType: 'text'
        })
        .done (function(obj){
            hideLoaderIdentity();
            console.log('Ответ получен'+obj);
            $("p#"+contentId).append("<br>"+obj);
        })
        .fail(function(xhr, status, error){
            hideLoaderIdentity();
    
            console.log('ajaxError xhr:', xhr); // выводим значения переменных
            console.log('ajaxError status:', status);
            console.log('ajaxError error:', error);
            console.log('Ошибка соединения при получении данных (GET)');
        });
        
        return false;
        
    });  
}

function init_post() 
{
    $('a.ajaxArticleBodyByPost').one('click', function(){
        var content = $(this).attr('data-contentId');
        showLoaderIdentity();
        $.ajax({
            url:'/ajax/showContentsHandler.php', 
            dataType: 'json',
            data: ({articleId: content}),
            method: 'POST'
        })
        .done (function(obj){
            hideLoaderIdentity();
            console.log('Ответ получен');
            $("p#"+content).append("<br>"+obj.content);
        })
        .fail(function(xhr, status, error){
            hideLoaderIdentity();
            console.log('Ошибка соединения с сервером (POST)');
            console.log('ajaxError xhr:', xhr); // выводим значения переменных
            console.log('ajaxError status:', status);
            console.log('ajaxError error:', error);
        });
        
        return false;
        
    });  
}

function new_load_post(){
    $('a.newAjaxArticleBodyByPost').one('click', function(){
        var articleId = $(this).attr('data-contentId');
        showLoaderIdentity();
        //console.log('hello');
        $.ajax({
           url: '/ajax/showContentsHandler.php',
           type: "POST",
           data: ({articleId: articleId}),
           dataType: 'json',  // тип возвращаемого значения
           
           success: function(data){
               console.log('Полученный ответ: ', data);
               console.log('Содержание: ', data.content);
               //$("p#"+articleId).html(data.content);
               $("p#"+articleId).append("<br>"+data.content);
               hideLoaderIdentity();
           },
           error: (function(xhr, status, error){
                console.log('Ошибка соединения с сервером (POST)');
                console.log('ajaxError xhr:', xhr); // выводим значения переменных
                console.log('ajaxError status:', status);
                console.log('ajaxError error:', error);
                hideLoaderIdentity();
            })
        });
        return false;
    });
}

function new_load_get(){
    $('a.newAjaxArticleBodyByGet').one('click', function(){
        var articleId = $(this).attr('data-contentId');
        showLoaderIdentity();
        //console.log('hello');
        $.ajax({
           url: '/ajax/showContentsHandler.php?articleId='+articleId,
           type: "GET",
           dataType: 'text',  // тип возвращаемого значения
           
           success: function(data){
               console.log('Полученный ответ: ', data);
               //$("p#"+articleId).html(data);
               $("p#"+articleId).append("<br>"+data);
               hideLoaderIdentity();
           },
           error: (function(xhr, status, error){
                $("p#"+articleId).text('Ошибка!');
                console.log('Ошибка соединения с сервером (GET)');
                console.log('ajaxError xhr:', xhr); // выводим значения переменных
                console.log('ajaxError status:', status);
                console.log('ajaxError error:', error);
                hideLoaderIdentity();
            })
        });
        return false;
    });
}