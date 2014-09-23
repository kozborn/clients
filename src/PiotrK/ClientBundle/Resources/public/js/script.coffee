jQuery(document).ready ($)->
  clients = new Clients

class Clients
  inputs: null
  button: null
  container: null
  url: null
  paginator: '.paginator'
  datetime: '.datetime'
  deleteLink: '.delete'
  messageContainer: '#message-container'
  @currentPage: 1
  constructor: () ->
    @inputs = $('input')
    $(@datetime).datepicker({dateFormat: "yy-mm-dd" })
    @button = $('button#search-result')
    @container = $('#report-container')
    
    @url = @inputs.parents('form').attr('action')
    @bindLinks()
    @inputs.bind 'keyup', @search
    @inputs.bind 'change', @search
    @button.bind 'click', @search

  bindLinks: () ->
    $(@paginator).find('a').bind 'click', @setCurrentPage
    $(@deleteLink).bind 'click', @removeClient

  setCurrentPage: (e) =>
    e.preventDefault()
    @currentPage = $(e.target).data('page')
    @search(e)

  search: (e) =>
    e.preventDefault()
    values = {}
    values.page = @currentPage
    @inputs.each (index, item) =>
      if item.value
        values[$(item).attr('name')] = item.value
    @post = $.ajax({
      url: @url,
      type: "POST",
      data: values
      })
      .done (response) =>
        @container.html(response)
        @bindLinks()
      .fail (jqHXR, response)=>
        console.log response

  removeClient: (e) =>
    e.preventDefault()
    deleteUrl = $(e.target).attr('href')
    @post = $.ajax({
      url: deleteUrl,
      type: "POST",
      })
      .done (response) =>
        $('.message-container').html('').show()
        $('.message-container').html($.parseJSON(response).message)
        setTimeout =>
            $('.message-container').fadeOut()
          , 1000


        @search(e)
      .fail (jqHXR, response)=>
        console.log response