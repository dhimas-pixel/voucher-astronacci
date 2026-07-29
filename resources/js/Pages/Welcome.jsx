import { Head, useForm } from "@inertiajs/react";
import axios from "axios";
import { useState } from "react";

export default function Welcome() {
    const { data, setData, reset } = useForm({
        crew_name: "",
        crew_id: "",
        flight_number: "",
        flight_date: "",
        aircraft_type: "",
    });

    const [generatedSeats, setGeneratedSeats] = useState(null);
    const [canGenerate, setCanGenerate] = useState(false);
    const [errorMessage, setErrorMessage] = useState("");
    const [successMessage, setSuccessMessage] = useState("");
    const [isLoading, setIsLoading] = useState(false);

    const handleSubmit = async (e) => {
        e.preventDefault();

        if (canGenerate) {
            onGenerate();
            return;
        }

        setIsLoading(true);
        setErrorMessage("");
        setSuccessMessage("");
        setGeneratedSeats(null);

        try {
            const response = await axios.post("/api/check", {
                flight_number: data.flight_number,
                flight_date: data.flight_date,
            });

            if (
                response.data.status === true &&
                response.data.data.exists === false
            ) {
                setSuccessMessage(response.data.message);
                setCanGenerate(true);
            }
        } catch (error) {
            if (error.response) {
                if (error.response.status === 400) {
                    setErrorMessage(error.response.data.message);
                } else if (error.response.status === 422) {
                    setErrorMessage(
                        error.response.data.message ||
                            "Periksa kembali kelengkapan data Anda.",
                    );
                } else {
                    setErrorMessage("Terjadi kesalahan pada server (500).");
                }
            } else {
                setErrorMessage(
                    "Gagal terhubung ke server. Periksa koneksi Anda.",
                );
            }
        } finally {
            setIsLoading(false);
        }
    };

    const onGenerate = async () => {
        setIsLoading(true);
        setErrorMessage("");
        setSuccessMessage("");
        setGeneratedSeats(null);

        try {
            const response = await axios.post("/api/generate", {
                crew_name: data.crew_name,
                crew_id: data.crew_id,
                flight_number: data.flight_number,
                flight_date: data.flight_date,
                aircraft_type: data.aircraft_type,
            });

            if (response.data.status === "success") {
                setSuccessMessage(response.data.message);
                setGeneratedSeats(response.data.data.seats);
                setCanGenerate(false);
            }
        } catch (error) {
            if (error.response) {
                if (error.response.status === 400) {
                    setErrorMessage(error.response.data.message);
                    setCanGenerate(false);
                } else if (error.response.status === 422) {
                    setErrorMessage(
                        error.response.data.message ||
                            "Periksa kembali kelengkapan data Anda.",
                    );
                } else {
                    setErrorMessage(
                        "Terjadi kesalahan pada server saat men-generate voucher.",
                    );
                }
            } else {
                setErrorMessage(
                    "Gagal terhubung ke server. Periksa koneksi Anda.",
                );
            }
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <>
            <Head title="Voucher App Astronacci" />

            <div className="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
                <div className="sm:mx-auto sm:w-full sm:max-w-md">
                    <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Voucher Seat Assignment
                    </h2>
                    <p className="mt-2 text-center text-sm text-gray-600">
                        Silakan masukkan detail penerbangan untuk mengalokasikan
                        kursi.
                    </p>
                </div>

                <div className="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                    <div className="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-gray-100">
                        {errorMessage && (
                            <div className="mb-4 bg-red-50 border-l-4 border-red-500 p-4">
                                <p className="text-sm text-red-700">
                                    {errorMessage}
                                </p>
                            </div>
                        )}

                        {successMessage && (
                            <div className="mb-4 bg-green-50 border-l-4 border-green-500 p-4">
                                <p className="text-sm text-green-700">
                                    {successMessage}
                                </p>
                            </div>
                        )}

                        <form className="space-y-6" onSubmit={handleSubmit}>
                            {/* Flight Number */}
                            <div>
                                <label
                                    htmlFor="flight_number"
                                    className="block text-sm font-medium text-gray-700"
                                >
                                    Flight Number
                                </label>
                                <div className="mt-1">
                                    <input
                                        id="flight_number"
                                        type="text"
                                        required
                                        placeholder="e.g., GA102"
                                        value={data.flight_number}
                                        onChange={(e) =>
                                            setData(
                                                "flight_number",
                                                e.target.value,
                                            )
                                        }
                                        className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    />
                                </div>
                            </div>

                            {/* Flight Date */}
                            <div>
                                <label
                                    htmlFor="flight_date"
                                    className="block text-sm font-medium text-gray-700"
                                >
                                    Flight Date
                                </label>
                                <div className="mt-1">
                                    <input
                                        id="flight_date"
                                        type="date"
                                        min={
                                            new Date()
                                                .toISOString()
                                                .split("T")[0]
                                        }
                                        required
                                        value={data.flight_date}
                                        onChange={(e) =>
                                            setData(
                                                "flight_date",
                                                e.target.value,
                                            )
                                        }
                                        className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    />
                                </div>
                            </div>

                            {/* Crew Name */}
                            <div>
                                <label
                                    htmlFor="crew_name"
                                    className="block text-sm font-medium text-gray-700"
                                >
                                    Crew Name
                                </label>
                                <div className="mt-1">
                                    <input
                                        id="crew_name"
                                        type="text"
                                        required
                                        value={data.crew_name}
                                        onChange={(e) =>
                                            setData("crew_name", e.target.value)
                                        }
                                        className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    />
                                </div>
                            </div>

                            {/* Crew ID */}
                            <div>
                                <label
                                    htmlFor="crew_id"
                                    className="block text-sm font-medium text-gray-700"
                                >
                                    Crew ID
                                </label>
                                <div className="mt-1">
                                    <input
                                        id="crew_id"
                                        type="text"
                                        required
                                        value={data.crew_id}
                                        onChange={(e) =>
                                            setData("crew_id", e.target.value)
                                        }
                                        className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    />
                                </div>
                            </div>

                            {/* Aircraft Type Dropdown */}
                            <div>
                                <label
                                    htmlFor="aircraft_type"
                                    className="block text-sm font-medium text-gray-700"
                                >
                                    Aircraft Type
                                </label>
                                <div className="mt-1">
                                    <select
                                        id="aircraft_type"
                                        required
                                        value={data.aircraft_type}
                                        onChange={(e) =>
                                            setData(
                                                "aircraft_type",
                                                e.target.value,
                                            )
                                        }
                                        className="block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    >
                                        <option value="" disabled>
                                            Select aircraft type
                                        </option>
                                        <option value="ATR">ATR</option>
                                        <option value="Airbus 320">
                                            Airbus 320
                                        </option>
                                        <option value="Boeing 737 Max">
                                            Boeing 737 Max
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {/* Submit Button */}
                            <div>
                                <button
                                    type="submit"
                                    disabled={isLoading}
                                    // Warna berubah: Kalau bisa generate warnanya Hijau, kalau check warnanya Indigo (Biru)
                                    className={`w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors
                                        ${
                                            canGenerate
                                                ? "bg-green-600 hover:bg-green-700 focus:ring-green-500"
                                                : "bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500"
                                        }
                                    `}
                                >
                                    {isLoading
                                        ? "Processing..."
                                        : canGenerate
                                          ? "Generate Vouchers"
                                          : "Check Flight"}
                                </button>
                            </div>
                        </form>

                        {generatedSeats && (
                            <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 animate-fade-in">
                                <div className="bg-white border border-gray-200 rounded-2xl shadow-2xl p-8 max-w-md w-full text-center relative animate-scale-up">
                                    <div className="mx-auto flex items-center justify-center w-12 h-12 rounded-full bg-green-100 text-green-600 mb-4">
                                        <svg
                                            className="w-6 h-6"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                                d="M5 13l4 4L19 7"
                                            ></path>
                                        </svg>
                                    </div>

                                    <h3 className="text-xl font-bold text-gray-800 mb-2">
                                        Voucher Berhasil Dibuat!
                                    </h3>
                                    <p className="text-sm text-gray-500 mb-6">
                                        Nomor penerbangan{" "}
                                        <span className="font-semibold text-gray-700">
                                            {data.flight_number}
                                        </span>{" "}
                                        ({data.aircraft_type})
                                    </p>

                                    <div className="flex justify-center items-center gap-4 mb-8">
                                        {generatedSeats.map((seat, index) => (
                                            <div
                                                key={index}
                                                className="flex flex-col items-center justify-center w-16 h-20 bg-indigo-50 text-indigo-700 font-black text-xl rounded-xl border-2 border-indigo-200 shadow-inner"
                                            >
                                                <span className="text-[10px] text-indigo-400 uppercase tracking-widest mb-1">
                                                    Seat
                                                </span>
                                                {seat}
                                            </div>
                                        ))}
                                    </div>

                                    <button
                                        type="button"
                                        onClick={() => {
                                            setGeneratedSeats(null);
                                            reset();
                                        }}
                                        className="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        Tutup & Selesai
                                    </button>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </>
    );
}
