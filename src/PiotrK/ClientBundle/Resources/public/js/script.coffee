jQuery(document).ready ($)->
  filter = new Filter

class Filter
  inputs: null
  button: null
  container: null
  url: null
  paginator: null
  @currentPage: 1
  constructor: () ->
    @inputs = $('input')
    $( ".datetime" ).datepicker({dateFormat: "yy-mm-dd" })
    @button = $('button#search-result')
    @container = $('#report-container')
    @url = "/app_dev.php/report-filter"
    @button.click @search
    @bindPagination()

  bindPagination: () ->
    $('.paginator').find('a').bind 'click', @setCurrentPage

  setCurrentPage: (e) =>
    e.preventDefault()
    @currentPage =  $(e.target).data('page')
    @search()

  search: () =>
    values = {}
    values.page = @currentPage
    @inputs.each (index, item) =>
      if item.value
        values[$(item).attr('name')] = item.value
    console.log values
    @post = $.ajax({
      url: @url,
      type: "POST",
      data: values
      })
      .done (response) =>
        @container.html(response)
        @bindPagination()
        # @updateValueContainer target,response
      .fail (jqHXR, response)=>
        console.log response
        # @failure(jqHXR, response)
