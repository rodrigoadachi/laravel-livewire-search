import './bootstrap';

Livewire.on('update-url', (params) => {
  const url = new URL(window.location);

  if (typeof params[0].query !== 'undefined' && !!params[0].query)
    url.searchParams.set('query', params[0].query);
  else
    url.searchParams.delete('query');

  if (typeof params[0].categories !== 'undefined' && !!params[0].categories.length) {
    url.searchParams.set('categories', Array.isArray(params[0].categories) ? params[0].categories.join(',') : params[0].categories);
  } else
    url.searchParams.delete('categories');

  if (typeof params[0].brands !== 'undefined' && !!params[0].brands.length)
    url.searchParams.set('brands', Array.isArray(params[0].brands) ? params[0].brands.join(',') : params[0].brands);
  else
    url.searchParams.delete('brands');

  if (typeof params[0].perPage !== 'undefined' && !!params[0].perPage)
    url.searchParams.set('perPage', params[0].perPage);
  else
    url.searchParams.delete('perPage');

  if (typeof params[0].sortField !== 'undefined' && !!params[0].sortField)
    url.searchParams.set('sortField', params[0].sortField);
  else
    url.searchParams.delete('sortField');

  if (typeof params[0].sortDirection !== 'undefined' && !!params[0].sortDirection)
    url.searchParams.set('sortDirection', params[0].sortDirection);
  else
    url.searchParams.delete('sortDirection');

  url.searchParams.set('page', typeof params[0].page !== 'undefined' ? params[0].page : 1);

  window.history.pushState({}, '', url);
});
