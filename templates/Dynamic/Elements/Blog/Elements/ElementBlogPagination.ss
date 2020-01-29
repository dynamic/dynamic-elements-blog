<% if $ShowPagination && $PaginatedList.Exists %>
    <nav>
        <% with $PaginatedList %>
            <% include SilverStripe\\Blog\\Pagination %>
        <% end_with %>
    </nav>
<% end_if %>
