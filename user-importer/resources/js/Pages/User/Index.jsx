import React from "react";
import { Head, router, usePage } from "@inertiajs/react";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import { Button } from "@/components/ui/button";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function Index() {
    const { users } = usePage().props;

    const handleImportClick = () => {
        router.get(route("user-imports.import"));
    };

    const handleExportClick = () => {
        window.location.href = route("users.export");
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 flex justify-between">
                    Users
                    <div className="flex space-x-2">
                        <Button onClick={handleImportClick}>
                            Import Users
                        </Button>
                        <Button onClick={handleExportClick}>
                            Export Users
                        </Button>
                    </div>
                </h2>
            }
        >
            <Head title="Users" />
            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            {users.data.length === 0 ? (
                                <p className="text-center text-gray-500">
                                    No users available.
                                </p>
                            ) : (
                                <>
                                    <Table>
                                        <TableHeader className="bg-gray-100">
                                            <TableRow>
                                                <TableHead className="w-[100px]">
                                                    ID
                                                </TableHead>
                                                <TableHead>NAME</TableHead>
                                                <TableHead>LASTNAME</TableHead>
                                                <TableHead>EMAIL</TableHead>
                                            </TableRow>
                                        </TableHeader>
                                        <TableBody>
                                            {users.data.map((user) => (
                                                <TableRow key={user.id}>
                                                    <TableCell className="font-medium">
                                                        {user.id}
                                                    </TableCell>
                                                    <TableCell>
                                                        {user.name}
                                                    </TableCell>
                                                    <TableCell>
                                                        {user.last_name}
                                                    </TableCell>
                                                    <TableCell>
                                                        {user.email}
                                                    </TableCell>
                                                </TableRow>
                                            ))}
                                        </TableBody>
                                    </Table>
                                    <div className="mt-4 flex justify-center space-x-2">
                                        {users.links.map((link, index) => (
                                            <Button
                                                key={index}
                                                variant={
                                                    link.active
                                                        ? "default"
                                                        : "ghost"
                                                }
                                                onClick={() =>
                                                    router.get(link.url)
                                                }
                                                disabled={!link.url}
                                            >
                                                {link.label
                                                    .replace("&laquo;", "<")
                                                    .replace("&raquo;", ">")}
                                            </Button>
                                        ))}
                                    </div>
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
