<article>
    <% if $ShowTitle %>
        <h1>
            <% if $ArchiveYear %>
                <%t SilverStripe\\Blog\\Model\\Blog.Archive 'Archive' %>:
                <% if $ArchiveDay %>
                    $ArchiveDate.Nice
                <% else_if $ArchiveMonth %>
                    $ArchiveDate.format('MMMM, y')
                <% else %>
                    $ArchiveDate.format('y')
                <% end_if %>
            <% else_if $CurrentTag %>
                <%t SilverStripe\\Blog\\Model\\Blog.Tag 'Tag' %>: $CurrentTag.Title
            <% else_if $CurrentCategory %>
                <%t SilverStripe\\Blog\\Model\\Blog.Category 'Category' %>: $CurrentCategory.Title
            <% else %>
                $Title
            <% end_if %>
        </h1>
    <% end_if %>

    <% if $Content %>
        $Content
    <% end_if %>

    <% if $PaginatedList.Exists %>
        <% loop $PaginatedList %>
            <% include SilverStripe\\Blog\\PostSummary %>
        <% end_loop %>
    <% else %>
        <p><%t SilverStripe\\Blog\\Model\\Blog.NoPosts 'There are no posts' %></p>
    <% end_if %>
</article>

<% if $ShowPagination && $PaginatedList.Exists %>
    <nav>
        <% with $PaginatedList %>
            <% include SilverStripe\\Blog\\Pagination %>
        <% end_with %>
    </nav>
<% end_if %>

<% if $ShowWidgets && $SideBarView %>
    <aside>
        <% include SilverStripe\\Blog\\BlogSideBar %>
    </aside>
<% end_if %>
