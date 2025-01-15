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
import { Badge } from "@/components/ui/badge";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function ImportHistoryIndex() {
    const { imports } = usePage().props;

    const formatStatus = (status) => {
        switch (status) {
            case "in_progress":
                return "In Progress";
            case "completed":
                return "Completed";
            case "failed":
                return "Failed";
            default:
                return "Unknown";
        }
    };

    const getBadgeColor = (status) => {
        switch (status) {
            case "in_progress":
                return "bg-yellow-500 text-black";
            case "completed":
                return "bg-green-500 text-white";
            case "failed":
                return "bg-red-500 text-white";
            default:
                return "bg-gray-500 text-white";
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Import History
                </h2>
            }
        >
            <Head title="Import History" />
            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            {imports.data.length === 0 ? (
                                <p className="text-center text-gray-500">
                                    No import history available.
                                </p>
                            ) : (
                                <>
                                    <Table>
                                        <TableHeader className="bg-gray-100">
                                            <TableRow>
                                                <TableHead className="w-[100px]">
                                                    ID
                                                </TableHead>
                                                <TableHead>PASSED</TableHead>
                                                <TableHead>FAILED</TableHead>
                                                <TableHead>STATUS</TableHead>
                                            </TableRow>
                                        </TableHeader>
                                        <TableBody>
                                            {imports.data.map((item) => (
                                                <TableRow key={item.id}>
                                                    <TableCell className="font-medium">
                                                        {item.id}
                                                    </TableCell>
                                                    <TableCell>
                                                        {item.passed}
                                                    </TableCell>
                                                    <TableCell>
                                                        {item.failed}
                                                    </TableCell>
                                                    <TableCell>
                                                        <Badge
                                                            className={`px-2 py-1 rounded ${getBadgeColor(
                                                                item.status
                                                            )}`}
                                                        >
                                                            {formatStatus(
                                                                item.status
                                                            )}
                                                        </Badge>
                                                    </TableCell>
                                                </TableRow>
                                            ))}
                                        </TableBody>
                                    </Table>
                                    <div className="mt-4 flex justify-center">
                                        {imports.links.map((link, index) => (
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
